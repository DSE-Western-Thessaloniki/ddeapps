<template>
    <div class="container"> <!-- Page preview -->
        <div class="btn-toolbar"> <!-- toolbar -->
            <div class="btn-toolbar mb-3" role="toolbar" aria-label="Preview toolbar">
                <div class="btn-group btn-group-lg mr-2" role="group" aria-label="First group">
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true"><span class="align-middle">{{ __('Zoom') }}:</span></button>
                    <select class="btn btn-dark    "
                            name="pagezoom"
                            v-on:change="setZoom"
                            >
                        <option v-for="zoom in zoomLevel"
                            :value="zoom"
                            :key="zoom"
                            :selected="zoom == '70%'">
                            {{zoom}}
                        </option>
                    </select>
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true"><span class="align-middle">{{ __('Record') }}:</span></button>
                    <button class="btn btn-dark" aria-disabled="true" @click="leftArrowClicked"><i class="fa fa-arrow-left"></i></button>
                    <button role="button" class="btn btn-dark" aria-disabled="true" id="current_record" @click="showCurrentRecordInput"><span class="align-middle">1</span></button>
                    <input type="text" class="btn-light d-none" id="current_record_input" size="3" @change="currentRecordInputChanged($event)">
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true"><span class="align-middle">/</span></button>
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true" id="last-record"><span class="align-middle">0</span></button>
                    <button class="btn btn-dark" aria-disabled="true" @click="rightArrowClicked"><i class="fa fa-arrow-right"></i></button>
                    <a :href="edit_mailmerge_url" class="btn btn-dark preview-toolbar-button" aria-disabled="true" data-toggle="tooltip" data-placement="bottom" title="Επεξεργασία εγγράφου"><i class="fas fa-pencil-alt"></i><br/><span>Επεξεργασία</span></a>
                    <a :href="print_url_draft" target="_blank" class="btn btn-dark preview-toolbar-button" aria-disabled="true" data-toggle="tooltip" data-placement="bottom" title="Εκτύπωση συγχωνευμένων εγγράφων (τρίπτυχο)"><i class="fab fa-firstdraft"></i><br/><span>Τρίπτυχο</span></a>
                    <a :href="print_url" target="_blank" class="btn btn-dark preview-toolbar-button" aria-disabled="true" data-toggle="tooltip" data-placement="bottom" title="Εκτύπωση συγχωνευμένων εγγράφων"><i class="fas fa-print"></i><br/><span>Εκτύπωση</span></a>
                    <button class="btn btn-dark preview-toolbar-button" aria-disabled="true" @click="saveMailMergeClicked" data-toggle="tooltip" data-placement="bottom" title="Αποθήκευση συγχωνευμένων εγγράφων"><i class="fas fa-mail-bulk"></i><br/><span>Αποθήκευση</span></button>
                </div>
            </div>
        </div>
        <div class="page" size="A4">
            <table class="table table-borderless">
                <tr>
                    <td class="w-50">
                        <p class="text-center"><img :src="logo_img" width="50"></p>
                        <p class="text-center" v-html="doc_logo_text_html"></p>
                        <table class="table table-borderless doc-address-col">
                            <tr>
                                <td class="no-wrap pr-1">Ταχ. Διεύθυνση:</td>
                                <td>{{ editor_address }}</td>
                            </tr>
                            <tr>
                                <td>Πληροφορίες:</td>
                                <td>{{ editor_name }}</td>
                            </tr>
                            <tr>
                                <td>Τηλέφωνο:</td>
                                <td>{{ editor_telephone }}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>{{ editor_email }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-50">
                        <table class="table table-borderless doc-recipient-col">
                            <tr>
                                <td>
                                    <p class="text-right" v-if="this.doc_ada"><b>ΑΔΑ: {{ doc_ada }}</b></p>
                                    <p class="text-right">Θεσσαλονίκη, {{ doc_date }}<br/>
                                                        Αρ. Πρωτ.: {{ protocol_num }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-bottom" id="recipients">
                                    <p class="font-weight-bold mb-0">ΠΡΟΣ</p>
                                    <p>[[ΕΠΩΝΥΜΟ]] [[ΟΝΟΜΑ]]<br/>
                                    ΚΛΑΔΟΥ: [[ΚΛΑΔΟΣ]]<br/>
                                    <span id="am">Α.Μ.</span>: [[ΑΜ]]<br/>
                                    (δια της σχολικής μονάδας)
                                    </p>
                                    <p class="font-weight-bold mb-0">ΚΟΙΝ</p>
                                    1. ΑΦ [[ΑΦ]]<br/>
                                    <span id="recipient-list"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <p class="font-weight-bold">Θέμα: «{{ doc_subject }}»</p>
            <p id="doc_text"></p>

            <table class="table table-borderless signature-table">
                <tr>
                    <td class="text-center" v-html="exact_copy_html">
                    </td>
                    <td class="text-center" v-html="signature_html">
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal" id="myModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Έλεγχος αποδεκτών αλληλογραφίας</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Γίνεται έλεγχος των αποδεκτών της αλληλογραφίας σας. Μόλις ολοκληρωθεί ο έλεγχος θα ενεργοποιηθεί το κουμπί της λήψης.</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :style="progress_style" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">{{ progress }}%</div>
                        </div>
                        <div id="error_msg"></div>
                        <br/>
                        <div id="unknown_recipients" class="d-none">
                            <p>Οι παρακάτω παραλήπτες δεν βρέθηκαν στο σύστημα για αντιστοίχιση με κωδικό σχολικής μονάδας.
                                Παρακαλούμε επιλέξτε από δίπλα αν η σχολική μονάδα έμφανίζεται με άλλο όνομα.
                            </p>
                            <table class="table-striped table-bordered">
                                <thead>
                                    <th>Όνομα</th>
                                    <th>Ποσοστό ταιριάσματος</th>
                                    <th>Αντιστοίχιση</th>
                                </thead>
                                <tbody>
                                    <tr v-for="unknown_recipient in unknown_recipients"
                                        :key="unknown_recipient.name"
                                        :class="unknown_recipient.color"
                                    >
                                        <td>{{unknown_recipient.name}}</td>
                                        <td><i v-if="unknown_recipient.icon" :class="unknown_recipient.icon"></i>{{unknown_recipient.percentage}}%</td>
                                        <td>
                                            <select name='recipient' @change='recipientSelectorChanged' v-model="ur_selected[unknown_recipient.name]">
                                                <option v-for="ur_option in ur_options" :key="ur_option.value" :value="ur_option.value">{{ ur_option.name }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Άκυρο</button>
                            <button type="button" class="btn btn-primary d-none" id="save_recipients" @click="saveRecipientsClicked">Αποθήκευση αντιστοίχισης</button>
                            <button type="button" class="btn btn-primary" id="save_mail_merge" @click="readySaveMailMergeClicked" disabled>
                                <div class="spinner-border" role="status">
                                <span class="sr-only">Working...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  props: {
    editor_address: String,
    editor_name: String,
    editor_telephone: String,
    editor_email: String,
    doc_logo_image: String,
    doc_logo_text: String,
    exact_copy_text: String,
    signature_text: String,
    protocol_num: String,
    doc_ada: String,
    doc_date: String,
    doc_subject: String,
    doc_text: String,
    doc_recipient_fields: String,
    xls_data: String,
    print_url: String,
    save_mail_merge_url: String,
    recipient_list_url: String,
    edit_mailmerge_url: String,
    store_many_url: String,
    app_url: String
  },
  mounted () {
    this.setZoom()
    this.getLastRecord()
    this.rec_text = $('#recipients').html()
    this.showCurrentRecordText()
  },
  data: function () {
    return {
      records: this.setIds(JSON.parse(this.xls_data)),
      current_record: 0,
      recipient_fields: JSON.parse(this.doc_recipient_fields),
      progress: 0,
      to_select: 0,
      unknown_recipients: [],
      ur_options: [],
      ur_selected: {},
      rec_text: ''
    }
  },
  watch: {
  },
  methods: {
    setZoom: function () {
      const transformOrigin = [0, 0]
      const zoom = parseInt($('select[name="pagezoom"').val())
      const el = $('div.page')
      const p = ['webkit', 'moz', 'ms', 'o']
      const s = 'scale(' + zoom / 100 + ')'
      const oString = (transformOrigin[0] * 100) + '% ' + (transformOrigin[1] * 100) + '%'

      for (let i = 0; i < p.length; i++) {
        el.css(p[i] + 'Transform', s)
        el.css(p[i] + 'TransformOrigin', oString)
      }

      el.css('transform', s)
      el.css('transformOrigin', oString)
    },

    showVal: function (a) {
      const zoomScale = Number(a) / 10
      setZoom(zoomScale, document.getElementsByClassName('container')[0])
    },

    setIds: function (data) {
      let i = 1
      data.forEach(el => {
        el.id = i
        i++
      })

      return data
    },

    getLastRecord: function () {
      $('#last-record span').html(this.records[this.records.length - 1].id)
    },

    currentRecordChanged: function (e) {
      this.current_record = e.target.value
      this.showCurrentRecordText()
    },

    replaceFields: function (text) {
      const pattern = /\[\[.+?\]\]/g
      const matches = []
      let result

      // Βρες όλες τις ετικέτες
      while ((result = pattern.exec(text)) !== null) {
        matches.push(result[0])
      };

      // Για κάθε ετικέτα κάνε αντικατάσταση με την αντίστοιχη τιμή
      const vueobj = this
      matches.forEach(function (match) {
        const field = match.slice(2, match.length - 2)
        if (typeof vueobj.records[vueobj.current_record][field] !== 'undefined') {
          text = text.replaceAll(match, vueobj.records[vueobj.current_record][field])
        } else {
          text = text.replaceAll(match, '')
        }
      })
      return text
    },

    showCurrentRecordText: function () {
      let text = this.doc_text
      text = this.replaceFields(text)
      $('#doc_text').html(text)

      text = this.rec_text
      text = this.replaceFields(text)
      $('#recipients').html(text)

      if (typeof this.records[this.current_record]['ΑΜ'] !== 'undefined') {
        const num = this.records[this.current_record]['ΑΜ']
        if (parseInt(num) < 1000000) {
          $('#am').html('Α.Μ.')
        } else {
          $('#am').html('Α.Φ.Μ.')
        }
      }

      let i = 2
      let recipient_list = ''
      const recipient_list_array = []
      const vueobj = this
      this.recipient_fields.forEach(function (field) {
        if (vueobj.records[vueobj.current_record][field] != undefined &&
                        vueobj.records[vueobj.current_record][field] != '' &&
                        (!recipient_list_array.includes(vueobj.records[vueobj.current_record][field]))) {
          recipient_list += i + '. ' + vueobj.records[vueobj.current_record][field] + '<br/>'
          recipient_list_array.push(vueobj.records[vueobj.current_record][field])
          i += 1
        }
      })
      $('#recipient-list').html(recipient_list)
    },

    showCurrentRecordInput: function () {
      $('#current_record').addClass('d-none')
      $('#current_record_input').removeClass('d-none')
      $('#current_record_input').focus()
    },

    currentRecordInputChanged: function (e) {
      const cur = e.target.value
      if (cur < 0 || cur > this.records[this.records.length - 1].id) {
        $('#current_record').removeClass('d-none')
        $('#current_record_input').addClass('d-none')
      } else {
        $('#current_record').removeClass('d-none')
        $('#current_record_input').addClass('d-none')
        $('#current_record').html(cur)
        this.current_record = cur - 1
        this.showCurrentRecordText()
      }
    },

    leftArrowClicked: function () {
      $('#current_record').removeClass('d-none')
      $('#current_record_input').addClass('d-none')
      if (this.current_record > 0) {
        this.current_record--
        $('#current_record').html(this.current_record + 1)
        $('#current_record_input').val(this.current_record + 1)
        this.showCurrentRecordText()
      }
    },

    rightArrowClicked: function () {
      $('#current_record').removeClass('d-none')
      $('#current_record_input').addClass('d-none')
      if (this.current_record < (this.records[this.records.length - 1].id - 1)) {
        this.current_record++
        $('#current_record').html(this.current_record + 1)
        $('#current_record_input').val(this.current_record + 1)
        this.showCurrentRecordText()
      }
    },
    saveMailMergeClicked: function () {
      // Αρχικοποίησε τιμές
      this.progress = 0
      $('#unknown_recipients').addClass('d-none')
      this.unknown_recipients = []
      this.ur_selected = {}
      $('#save_mail_merge').html('<div class="spinner-border" role="status"><span class="sr-only">Working...</span></div>')
      $('#save_mail_merge').prop('disabled', true)

      // Εμφάνισε το modal
      $('#myModal').modal({
        backdrop: 'static',
        keyboard: false,
        focus: true,
        show: true
      })

      let recipients = []
      const vueobj = this
      let unknown = 0

      $.get(this.recipient_list_url, function () {
      })
        .done(function (data) {
          recipients = data
          const max = vueobj.records[vueobj.records.length - 1].id

          // Φτιάξε το combobox με τους διαθέσιμους παραλήπτες
          vueobj.ur_options = [{ value: -1, name: 'Παρακαλώ επιλέξτε' }]
          recipients.forEach(function (recipient) {
            vueobj.ur_options.push({ value: recipient.code, name: recipient.name })
          })

          // Προετοιμασία fuzzy search
          const options = {
            // isCaseSensitive: false,
            includeScore: true,
            // shouldSort: true,
            // includeMatches: false,
            // findAllMatches: false,
            // minMatchCharLength: 1,
            // location: 0,
            threshold: 0.4,
            distance: 10,
            // useExtendedSearch: false,
            // ignoreLocation: false,
            // ignoreFieldNorm: false,
            keys: [
              'name'
            ]
          }
          const fuse = new Fuse.default(recipients, options)

          vueobj.delayedLoop(vueobj.records, 20, function (item, index) {
            const doc_fields = JSON.parse(vueobj.doc_recipient_fields)
            doc_fields.push('ΑΦ')
            doc_fields.forEach(function (field) {
              // Κοιτάει για την τιμή του πεδίου στο όνομα του παραλήπτη
              if (field !== null &&
                                    (item[field] != '') &&
                                    !(recipients.map((x) => x.name).includes(item[field])) &&
                                    !vueobj.unknown_recipients.map((x) => x.name).includes(item[field])) {
                $('#save_recipients').removeClass('d-none')
                unknown++
                vueobj.ur_selected[item[field]] = -1

                // Fuzzy search
                const pattern = item[field]
                const results = fuse.search(pattern)
                let color = ''
                let icon = ''
                var percentage
                if (results.length) {
                  vueobj.ur_selected[item[field]] = results[0].item.code

                  // Σημείωσε με χρώμα τα σκορ στον πίνακα
                  if (results[0].score < 0.2) {
                    color = 'bg-success'
                  } else if (results[0].score < 0.5) {
                    color = 'bg-warning'
                  } else {
                    color = 'bg-danger'
                  }

                  // Έλεγχος αριθμών
                  let num1 = item[field].slice(0, 5).match(/\d+/g)
                  let num2 = results[0].item.name.slice(0, 5).match(/\d+/g)
                  num1 = num1 == null ? 0 : num1
                  num2 = num2 == null ? 0 : num2
                  var percentage = Math.round((1 - parseFloat(results[0].score)) * 10000) / 100
                  if (!(parseInt(num1) == parseInt(num2))) {
                    icon = 'fas fa-exclamation-triangle'
                  }
                } else {
                  percentage = 0
                  color = 'bg-danger'
                  vueobj.to_select++
                }

                $('#unknown_recipients').removeClass('d-none')
                vueobj.unknown_recipients.push({ name: item[field], icon: icon, color: color, percentage: percentage })
              }
            })
            vueobj.update_progress(index + 1, max)
            if ((index + 1) == max) {
              $('#save_mail_merge').html('Λήψη')
              if (unknown == 0) {
                $('#save_mail_merge').prop('disabled', false)
              }
              vueobj.sort_table()
            }
          })
        })
        .fail(function (data) {
          $('#error_msg').html('Error retrieving recipient list!')
          $('#error_msg').addClass('alert')
          $('#error_msg').addClass('alert-danger')
        })
    },
    sort_table: function () {
      this.unknown_recipients.sort(function (a, b) {
        return b.percentage - a.percentage
      })
    },
    update_progress: function (value, max) {
      this.progress = Math.round(((parseInt(value) / parseInt(max) * 100) + Number.EPSILON) * 100) / 100
    },
    check_record_recipients: function (record, recipients) {
      for (let i = 0; i < 100; i++);
    },
    delayedLoop: function (collection, delay, callback, context) {
      context = context || null

      let i = 0
      const nextIteration = function () {
        if (i === collection.length) {
          return
        }

        callback.call(context, collection[i], i)
        i++
        setTimeout(nextIteration, delay)
      }

      nextIteration()
    },
    saveRecipientsClicked: function () {
      if (this.to_select) {
        alert('Παρακαλώ επιλέξτε αντιστοίχιση για όλους τους παραλήπτες!')
      } else {
        document.getElementById('save_recipients').classList.add('disabled')
        const data = []
        document.querySelectorAll('#unknown_recipients table tr').forEach((row) => {
          data.push({
            name: (row.children[0]).innerText,
            code: row.children[2].children[0].selectedOptions[0].value,
            link: row.children[2].children[0].selectedOptions[0].innerText
          })
        })
        $.post({
          url: this.store_many_url,
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: { many: data }
        })
          .done(function (data) {
            $('#save_mail_merge').prop('disabled', false)
          })
          .fail(function (data) {
            alert('Failed saving data')
            $('#save_recipients').removeClass('disabled')
          })
      }
    },
    recipientSelectorChanged: function () {
      let remaining = 0
      for (const [key, value] of Object.entries(this.ur_selected)) {
        if (value == -1) { remaining++ }
      }
      this.to_select = remaining
    },
    readySaveMailMergeClicked: function () {
      $('#save_mail_merge').html('<div class="spinner-border" role="status"><span class="sr-only">Working...</span></div>')
      $('#save_mail_merge').prop('disabled', true)
      window.location.assign(this.save_mail_merge_url)
      // $("#save_mail_merge").html("Λήψη");
    }
  },
  computed: {
    zoomLevel: function () {
      const lvl = []
      for (let i = 10; i <= 100; i += 10) {
        lvl.push(i + '%')
      }
      return lvl
    },
    logo_img: function () {
      return this.app_url + '/images/' + this.doc_logo_image
    },
    doc_logo_text_html: function () {
      return this.doc_logo_text.replace(/\n/g, '<br/>')
    },
    /* locale_date: function() {
                var date = new Date(this.doc_date);
                return date.toLocaleDateString();
            }, */
    exact_copy_html: function () {
      return this.exact_copy_text.replace(/\n/g, '<br/>')
    },
    signature_html: function () {
      return this.signature_text.replace(/\n/g, '<br/>')
    },
    progress_style: function () {
      return 'width: ' + this.progress + '%;'
    },
    print_url_draft: function () {
      return this.print_url + '?draft=true'
    }
  }
}
</script>
