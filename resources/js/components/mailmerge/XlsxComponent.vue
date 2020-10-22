<template>
    <div class="container">
        <div class="form-group">
            <input class="form-control-file" type="file" multiple="false" id="sheetjs-input" accept=".xlsx,.xls,.csv" @change="onchange"/>
            <br/>
            <div id="out-table"></div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            mergefields: Array,
        },
        mounted() {
            console.log('XlsxComponent mounted.')
        },
        data: function() {
            return {
                mydata: [],
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
                    vueobj.mydata = XLSX.utils.sheet_to_json(ws)
                    //vueobj.mergefields = vueobj.mydata
                    vueobj.$emit('setmergefields', vueobj.getHeader)

                    /* update table */
                    var table = document.getElementById('out-table')
                    table.innerHTML = HTML
                    table.getElementsByTagName('table')[0].setAttribute('class', 'table-striped table-bordered table-responsive')
                }

                reader.readAsArrayBuffer(file)

            },
        },
        computed: {
            getHeader() {
                return(this.mydata.length ? Object.keys(this.mydata[0]) : [])
            }
        },
    }
</script>
