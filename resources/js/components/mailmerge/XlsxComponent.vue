<template>
    <div class="container">
        <div class="form-group">
            <input class="form-control-file" type="file" multiple="false" id="sheetjs-input" accept=".xlsx,.xls,.csv" @change="onchange"/>
            <br/>
            <div id="out-table"
                @contextmenu.prevent="$refs.menu.open($event, {
                    item: $event.target,
                    selected: $event.target.classList.contains('recipient-col')
                })"
            ></div>
        </div>

        <vue-context ref="menu" v-slot="{ data }">
            <li v-if="data && data.selected">
                <a @click.prevent="onClick($event, data.item, 'unselcol')">{{__('Remove column from recipient list')}}</a>
            </li>
            <li v-else>
                <a @click.prevent="onClick($event, data.item, 'selcol')">{{__('Select column as recipient list')}}</a>
            </li>
        </vue-context>
        <input type="text" class="form-control" hidden id="xlsxdata" name="xlsxdata" :value="getData">
        <input type="text" class="form-control" hidden id="mergefields" name="mergefields" :value="getMergeFields">
    </div>
</template>

<script>
    import VueContext from 'vue-context';


    export default {
        components: { VueContext },
        props: {
            mergefields: Array,
        },
        mounted() {
            console.log('XlsxComponent mounted.')
        },
        data: function() {
            return {
                xlsxdata: [],
                selected_cols: [],
            }
        },
        watch: {
        },
        methods: {
            onchange: function(evt) {
                var file
                var files = evt.target.files

                if (!files || files.length == 0) return

                file = files[0]
                var vueobj = this

                var reader = new FileReader()
                reader.onload = function (e) {
                    // pre-process data
                    var binary = ""
                    var bytes = new Uint8Array(e.target.result)
                    var length = bytes.byteLength
                    for (var i = 0; i < length; i++) {
                        binary += String.fromCharCode(bytes[i])
                    }

                    /* read workbook */
                    var wb = XLSX.read(binary, {type: 'binary'})

                    /* grab first sheet */
                    var wsname = wb.SheetNames[0]
                    var ws = wb.Sheets[wsname]

                    /* generate HTML */
                    var HTML = XLSX.utils.sheet_to_html(ws)
                    vueobj.xlsxdata = XLSX.utils.sheet_to_json(ws)
                    vueobj.$emit('setmergefields', vueobj.getHeader)

                    /* update table */
                    var table = document.getElementById('out-table')
                    table.innerHTML = HTML
                    table.getElementsByTagName('table')[0].setAttribute('class', 'table-striped table-bordered table-responsive')
                }

                reader.readAsArrayBuffer(file)

            },
            onClick(e, item, code) {
                switch(code) {
                    case 'selcol':
                        var col = /[A-Z]+/.exec(item.id)
                        if (col) {
                            this.selected_cols.push(Object.keys(this.xlsxdata[0])[this.calcColumn(col[0])])
                            this.toggleColorSelectedCol(col[0])
                        }
                        break
                    case 'unselcol':
                        var col = /[A-Z]+/.exec(item.id)
                        if (col) {
                            var colidx = this.selected_cols.indexOf(Object.keys(this.xlsxdata[0])[this.calcColumn(col[0])])
                            this.selected_cols.splice(colidx, 1)
                            this.toggleColorSelectedCol(col[0])
                        }
                        break
                    default:
                        alert(`You clicked "${e.target.innerHTML}"!`)
                }

            },
            calcColumn(str) {
                if (str.length == 1) {
                    return (str[0].charCodeAt() - "A".charCodeAt())
                }
                return ((str[0][0].charCodeAt() - "A".charCodeAt() + 1) * 26 + str[0][1].charCodeAt() - "A".charCodeAt())
            },
            toggleColorSelectedCol(col) {
                var HTML = document.getElementById('out-table')
                var tds = HTML.querySelectorAll("td[id^='sjs-"+col+"']")
                tds.forEach(function(td) {
                    td.classList.toggle("recipient-col")
                })
            },
        },
        computed: {
            getHeader() {
                return(this.xlsxdata.length ? Object.keys(this.xlsxdata[0]) : [])
            },
            getData() {
                return JSON.stringify(this.xlsxdata);
            },
            getMergeFields() {
                return JSON.stringify(this.selected_cols);
            }
        },
    }
</script>
