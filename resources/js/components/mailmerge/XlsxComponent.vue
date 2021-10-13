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
        <input type="text" class="form-control" hidden id="xlsxdata_header" name="xlsxdata_header" :value="getDataHeader">
        <input type="text" class="form-control" hidden id="mergefields" name="mergefields" :value="getMergeFields">
    </div>
</template>

<script>
import VueContext from 'vue-context'

export default {
  components: { VueContext },
  props: {
    docdata: String,
    docdataheader: String,
    mfields: String
  },
  mounted () {
    console.log('XlsxComponent mounted.')
  },
  data: function () {
    return {
      xlsxdata: [],
      xlsxdata_header: [],
      selected_cols: [],
      necessary_cols: ['ΑΜ', 'ΟΝΟΜΑ', 'ΕΠΩΝΥΜΟ', 'ΚΛΑΔΟΣ', 'ΑΦ']
    }
  },
  watch: {
  },
  methods: {
    onchange: function (evt) {
      const files = evt.target.files

      if (!files || files.length === 0) return

      const file = files[0]
      const vueobj = this

      const reader = new FileReader()
      reader.onload = function (e) {
        // pre-process data
        let binary = ''
        const bytes = new Uint8Array(e.target.result)
        const length = bytes.byteLength
        for (let i = 0; i < length; i++) {
          binary += String.fromCharCode(bytes[i])
        }

        /* read workbook */
        const wb = XLSX.read(binary, { type: 'binary' })

        /* grab first sheet */
        const wsname = wb.SheetNames[0]
        const ws = wb.Sheets[wsname]

        let xlsxjson = XLSX.utils.sheet_to_json(ws, { defval: '' })
        // Trim, trim and more trim
        xlsxjson = JSON.parse(JSON.stringify(xlsxjson).replace(/"\s+|\s+"/g, '"'))
        xlsxjson.forEach(function (row) {
          row = Object.keys(row).map(k => row[k] = typeof row[k] === 'string' ? row[k].trim().replace(/\s{2,}/g, ' ') : row[k])
        })
        vueobj.xlsxdata = xlsxjson
        vueobj.xlsxdata_header = vueobj.getHeader
        vueobj.selected_cols = []
        if (!vueobj.necessary_fields_exist(vueobj.xlsxdata_header)) {
          vueobj.$emit('missingfields', true)
        } else {
          vueobj.$emit('missingfields', false)
        }

        vueobj.xlsxToTable(vueobj, ws)
      }

      reader.readAsArrayBuffer(file)
    },

    onClick (e, item, code) {
      console.log(item, code)
      const col = /[A-Z]+/.exec(item.id)
      switch (code) {
        case 'selcol':
          if (col) {
            this.selected_cols.push(this.xlsxdata_header[this.calcColumn(col[0])])
            this.toggleColorSelectedCol(col[0])
          }
          break
        case 'unselcol':
          if (col) {
            const colidx = this.selected_cols.indexOf(this.xlsxdata_header[this.calcColumn(col[0])])
            this.selected_cols.splice(colidx, 1)
            this.toggleColorSelectedCol(col[0])
          }
          break
        default:
          alert(`You clicked "${e.target.innerHTML}"!`)
      }
    },

    calcColumn (str) {
      if (str.length == 1) {
        return (str[0].charCodeAt() - 'A'.charCodeAt())
      }
      return ((str[0].charCodeAt() - 'A'.charCodeAt() + 1) * 26 + str[1].charCodeAt() - 'A'.charCodeAt())
    },

    toggleColorSelectedCol (col) {
      const HTML = document.getElementById('out-table')
      const tds = HTML.querySelectorAll("td[id^='sjs-" + col + "']")
      tds.forEach(function (td) {
        td.classList.toggle('recipient-col')
      })
    },

    parseDocData () {
      if (typeof this.docdata !== 'undefined') {
        if (this.docdata != '') {
          this.xlsxdata = JSON.parse(this.docdata)
          this.xlsxdata_header = JSON.parse(this.docdataheader)
          this.selected_cols = JSON.parse(this.mfields)
          const ws = XLSX.utils.json_to_sheet(this.xlsxdata, { header: this.xlsxdata_header })
          this.xlsxToTable(this, ws)
          const vueobj = this
          this.selected_cols.forEach(function (field) {
            vueobj.toggleColorSelectedCol(String.fromCharCode('A'.charCodeAt() + vueobj.xlsxdata_header.indexOf(field)))
          })
        }
      }
    },

    necessary_fields_exist (fields) {
      const found_cols = this.necessary_cols.slice()

      fields.forEach((field) => {
        if (found_cols.includes(field)) {
          found_cols.splice(found_cols.indexOf(field), 1)
        }
      })

      if (found_cols.length) {
        return false
      }
      return true
    },

    xlsxToTable (obj, ws) {
      /* generate HTML */
      const HTML = XLSX.utils.sheet_to_html(ws)
      obj.$emit('setmergefields', obj.getHeader)

      /* update table */
      const table = document.getElementById('out-table')
      table.innerHTML = HTML
      table.getElementsByTagName('table')[0].setAttribute('class', 'table-striped table-bordered table-responsive')
    }
  },
  computed: {
    getHeader () {
      return (this.xlsxdata.length ? Object.keys(this.xlsxdata[0]) : [])
    },
    getData () {
      return JSON.stringify(this.xlsxdata)
    },
    getDataHeader () {
      return JSON.stringify(this.xlsxdata_header)
    },
    getMergeFields () {
      return JSON.stringify(this.selected_cols)
    }
  }
}
</script>
