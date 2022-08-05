<template>
    <div class="container">
        <div class="form-group mb-3">
            <input class="form-control-file mb-3" type="file" multiple="false" id="sheetjs-input"
                accept=".xlsx,.xls,.csv" @change="onchange" />
            <br />
            <div id="out-table" @contextmenu.prevent="
                menu.open($event, {
                    item: $event.target,
                    selected: ($event.target as HTMLElement).classList.contains(
                        'recipient-col'
                    )
                })
            "></div>
        </div>

        <vue-context ref="menu" v-slot="{ data }: {
            data: {
                item: EventTarget,
                    selected: boolean,
                                                                                                    }
        }">
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
        <input type="text" class="form-control" hidden id="xlsxdata" name="xlsxdata" :value="getData" />
        <input type="text" class="form-control" hidden id="xlsxdata_header" name="xlsxdata_header"
            :value="getDataHeader" />
        <input type="text" class="form-control" hidden id="mergefields" name="mergefields" :value="getMergeFields" />
    </div>
</template>

<script lang="ts">
export default defineComponent({
    name: "XlsxComponent",
});
</script>

<script setup lang="ts">
import VueContext from "@madogai/vue-context";
import { ref, onMounted, computed, defineComponent, Ref } from "vue";
import * as XLSX from "xlsx";
import __ from "../../trans";

const props = defineProps<{
    docdata: string,
    docdataheader: string,
    mfields: string
}>();

const emit = defineEmits(["missingfields", "setmergefields"]);

onMounted(() => console.log("XlsxComponent mounted."));

const xlsxdata: Ref<object[]> = ref([]);
const xlsxdata_header: Ref<string[]> = ref([]);
const selected_cols: Ref<string[]> = ref([]);
const necessary_cols = ["ΑΜ", "ΟΝΟΜΑ", "ΕΠΩΝΥΜΟ", "ΚΛΑΔΟΣ", "ΑΦ"];
const menu: Ref<typeof VueContext> = ref();

const onchange = (event: Event) => {
    if (!(event.target instanceof HTMLInputElement)) return;

    const files = event.target?.files;

    if (!files || files.length === 0) return;

    const file = files[0];

    const reader = new FileReader();
    reader.onload = function (e) {
        // pre-process data
        let binary = "";

        if (!e.target || !(e.target.result instanceof ArrayBuffer)) return;

        const bytes = new Uint8Array(e.target.result);
        const length = bytes.byteLength;
        for (let i = 0; i < length; i++) {
            binary += String.fromCharCode(bytes[i]);
        }

        /* read workbook */
        const workBook = XLSX.read(binary, {
            type: "binary",
            cellDates: true,
            dateNF: "dd/mm/yyyy"
        });

        /* grab first sheet */
        const workSheetName = workBook.SheetNames[0];
        const workSheet = workBook.Sheets[workSheetName];

        let xlsx_json: XLSX_JSON[] = XLSX.utils.sheet_to_json(workSheet, { defval: "", raw: false });
        // Trim, trim and more trim
        xlsx_json = JSON.parse(
            JSON.stringify(xlsx_json).replace(/"\s+|\s+"/g, '"')
        );
        xlsx_json.forEach(function (row) {
            Object.keys(row).forEach(function (k) {
                row[k] =
                    typeof row[k] === "string"
                        ? (row[k] as string).trim().replace(/\s{2,}/g, " ")
                        : row[k];
                const newKey =
                    typeof k === "string"
                        ? k.trim().replace(/\s{2,}/g, " ")
                        : k;
                if (newKey !== k) {
                    const ownProperty = Object.getOwnPropertyDescriptor(row, k);

                    if (ownProperty !== undefined) {
                        Object.defineProperty(
                            row,
                            newKey,
                            ownProperty
                        );
                    }

                    delete row[k];
                }
            });
        });
        xlsxdata.value = xlsx_json;
        xlsxdata_header.value = getHeader.value;
        selected_cols.value = [];
        if (!necessary_fields_exist(xlsxdata_header.value)) {
            emit("missingfields", true);
        } else {
            emit("missingfields", false);
        }

        xlsxToTable(workSheet);
    };

    reader.readAsArrayBuffer(file);
};

const onClick = (e: MouseEvent, item: EventTarget, code: string) => {
    const col = /[A-Z]+/.exec((item as HTMLElement).id);
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
                const columnIndex = selected_cols.value.indexOf(
                    xlsxdata_header.value[calcColumn(col[0])]
                );
                selected_cols.value.splice(columnIndex, 1);
                toggleColorSelectedCol(col[0]);
            }
            break;
        default:
            alert(`You clicked "${(e.target as HTMLElement)?.innerHTML}"!`);
    }
};

const calcColumn = (str: string) => {
    if (str.length == 1) {
        return str[0].charCodeAt(0) - "A".charCodeAt(0);
    }
    return (
        (str[0].charCodeAt(0) - "A".charCodeAt(0) + 1) * 26 +
        str[1].charCodeAt(0) - "A".charCodeAt(0)
    );
};

const toggleColorSelectedCol = (col: string) => {
    const HTML = document.getElementById("out-table");
    if (HTML instanceof HTMLElement) {
        const tds = HTML.querySelectorAll("td[id^='sjs-" + col + "']");
        tds.forEach(function (td) {
            td.classList.toggle("recipient-col");
        });
    }
};

const parseDocData = () => {
    if (typeof props.docdata !== "undefined") {
        if (props.docdata != "") {
            xlsxdata.value = JSON.parse(props.docdata);
            xlsxdata_header.value = JSON.parse(props.docdataheader);
            selected_cols.value = JSON.parse(props.mfields);
            const workSheet = XLSX.utils.json_to_sheet(xlsxdata.value, {
                header: xlsxdata_header.value,
            });
            xlsxToTable(workSheet);
            selected_cols.value.forEach(function (field) {
                toggleColorSelectedCol(
                    String.fromCharCode(
                        "A".charCodeAt(0) +
                        xlsxdata_header.value.indexOf(field)
                    )
                );
            });
        }
    }
};

const necessary_fields_exist = (fields: string[]) => {
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

const xlsxToTable = (workSheet: XLSX.WorkSheet) => {
    /* generate HTML */
    const HTML = XLSX.utils.sheet_to_html(workSheet);
    emit("setmergefields", getHeader.value);

    /* update table */
    const table = document.getElementById("out-table");
    if (table instanceof HTMLElement) {
        table.innerHTML = HTML;
        table
            .getElementsByTagName("table")[0]
            .setAttribute(
                "class",
                "table-striped table-bordered table-responsive"
            );
    } else {
        alert("Unable to find element out-table");
    }
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
