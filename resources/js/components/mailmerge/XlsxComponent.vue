<template>
    <div class="container">
        <div class="form-group">
            <input
                class="form-control-file"
                type="file"
                multiple="false"
                id="sheetjs-input"
                accept=".xlsx,.xls,.csv"
                @change="onchange"
            />
            <br />
            <div
                id="out-table"
                @contextmenu.prevent="
                    $refs.menu.open($event, {
                        item: $event.target,
                        selected: $event.target.classList.contains(
                            'recipient-col'
                        )
                    })
                "
            ></div>
        </div>

        <vue-context ref="menu" v-slot="{ data }">
            <li v-if="data && data.selected">
                <a @click.prevent="onClick($event, data.item, 'deselect_column')">{{
                    __("Remove column from recipient list")
                }}</a>
            </li>
            <li v-else>
                <a @click.prevent="onClick($event, data.item, 'select_column')">{{
                    __("Select column as recipient list")
                }}</a>
            </li>
        </vue-context>
        <input
            type="text"
            class="form-control"
            hidden
            id="xlsxdata"
            name="xlsxdata"
            :value="getData"
        />
        <input
            type="text"
            class="form-control"
            hidden
            id="xlsxdata_header"
            name="xlsxdata_header"
            :value="getDataHeader"
        />
        <input
            type="text"
            class="form-control"
            hidden
            id="mergefields"
            name="mergefields"
            :value="getMergeFields"
        />
    </div>
</template>

<script>
export default defineComponent({
    name: "XlsxComponent",
});
</script>

<script setup>
import VueContext from "@madogai/vue-context";
import { ref, onMounted, computed, defineComponent } from "vue";
import * as XLSX from "xlsx";

const props = defineProps({
    docdata: String,
    docdataheader: String,
    mfields: String
});

const emit = defineEmits(["missingfields", "setmergefields"]);

onMounted(() => console.log("XlsxComponent mounted."));

const xlsxdata = ref([]);
const xlsxdata_header = ref([]);
const selected_cols = ref([]);
const necessary_cols = ["ΑΜ", "ΟΝΟΜΑ", "ΕΠΩΝΥΜΟ", "ΚΛΑΔΟΣ", "ΑΦ"];

const onchange = (evt) => {
    const files = evt.target.files;

    if (!files || files.length === 0) return;

    const file = files[0];

    const reader = new FileReader();
    reader.onload = function(e) {
        // pre-process data
        let binary = "";
        const bytes = new Uint8Array(e.target.result);
        const length = bytes.byteLength;
        for (let i = 0; i < length; i++) {
            binary += String.fromCharCode(bytes[i]);
        }

        /* read workbook */
        const wb = XLSX.read(binary, {
            type: "binary",
            cellDates: true,
            dateNF: "dd/mm/yyyy"
        });

        /* grab first sheet */
        const wsname = wb.SheetNames[0];
        const ws = wb.Sheets[wsname];

        let xlsxjson = XLSX.utils.sheet_to_json(ws, { defval: "", raw: false });
        // Trim, trim and more trim
        xlsxjson = JSON.parse(
            JSON.stringify(xlsxjson).replace(/"\s+|\s+"/g, '"')
        );
        xlsxjson.forEach(function(row) {
            row = Object.keys(row).forEach(function(k) {
                row[k] =
                    typeof row[k] === "string"
                        ? row[k].trim().replace(/\s{2,}/g, " ")
                        : row[k];
                const newKey =
                    typeof k === "string"
                        ? k.trim().replace(/\s{2,}/g, " ")
                        : k;
                if (newKey !== k) {
                    Object.defineProperty(
                        row,
                        newKey,
                        Object.getOwnPropertyDescriptor(row, k)
                    );
                    delete row[k];
                }
            });
        });
        xlsxdata.value = xlsxjson;
        xlsxdata_header.value = getHeader.value;
        selected_cols.value = [];
        if (!necessary_fields_exist(xlsxdata_header.value)) {
            emit("missingfields", true);
        } else {
            emit("missingfields", false);
        }

        xlsxToTable(ws);
    };

    reader.readAsArrayBuffer(file);
};

const onClick = (e, item, code) => {
    const col = /[A-Z]+/.exec(item.id);
    switch (code) {
        case "select_column":
            if (col) {
                selected_cols.value.push(
                    xlsxdata_header.value[calcColumn(col[0])]
                );
                toggleColorSelectedCol(col[0]);
            }
            break;
        case "deselect_column":
            if (col) {
                const colidx = selected_cols.value.indexOf(
                    xlsxdata_header.value[calcColumn(col[0])]
                );
                selected_cols.value.splice(colidx, 1);
                toggleColorSelectedCol(col[0]);
            }
            break;
        default:
            alert(`You clicked "${e.target.innerHTML}"!`);
    }
};

const calcColumn = (str) => {
    if (str.length == 1) {
        return str[0].charCodeAt() - "A".charCodeAt();
    }
    return (
        (str[0].charCodeAt() - "A".charCodeAt() + 1) * 26 +
        str[1].charCodeAt() - "A".charCodeAt()
    );
};

const toggleColorSelectedCol = (col) => {
    const HTML = document.getElementById("out-table");
    const tds = HTML.querySelectorAll("td[id^='sjs-" + col + "']");
    tds.forEach(function(td) {
        td.classList.toggle("recipient-col");
    });
};

const parseDocData = () => {
    if (typeof props.docdata !== "undefined") {
        if (props.docdata != "") {
            xlsxdata.value = JSON.parse(props.docdata);
            xlsxdata_header.value = JSON.parse(props.docdataheader);
            selected_cols.value = JSON.parse(props.mfields);
            const ws = XLSX.utils.json_to_sheet(xlsxdata.value, {
                header: xlsxdata_header.value,
            });
            xlsxToTable(ws);
            selected_cols.value.forEach(function(field) {
                toggleColorSelectedCol(
                    String.fromCharCode(
                        "A".charCodeAt() +
                            xlsxdata_header.value.indexOf(field)
                    )
                );
            });
        }
    }
};

const necessary_fields_exist = (fields) => {
    const found_cols = necessary_cols.slice();

    fields.forEach(field => {
        if (found_cols.includes(field)) {
            found_cols.splice(found_cols.indexOf(field), 1);
        }
    });

    if (found_cols.length) {
        return false;
    }
    return true;
};

const xlsxToTable = (ws) => {
    /* generate HTML */
    const HTML = XLSX.utils.sheet_to_html(ws);
    emit("setmergefields", getHeader.value);

    /* update table */
    const table = document.getElementById("out-table");
    table.innerHTML = HTML;
    table
        .getElementsByTagName("table")[0]
        .setAttribute(
            "class",
            "table-striped table-bordered table-responsive"
        );
};

const getHeader = computed(() => {
    return xlsxdata.value.length ? Object.keys(xlsxdata.value[0]) : [];
});

const getData = computed(() => {
    return JSON.stringify(xlsxdata.value);
});

const getDataHeader = computed(() => {
    return JSON.stringify(xlsxdata_header.value);
});

const getMergeFields = computed(() => {
    return JSON.stringify(selected_cols.value);
});

defineExpose({
    parseDocData,
});

</script>
