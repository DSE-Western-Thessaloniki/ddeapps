<template>
    <div class="container"> <!-- Page preview -->
        <div class="btn-toolbar"> <!-- toolbar -->
            <div class="btn-toolbar mb-3" role="toolbar" aria-label="Preview toolbar">
                <div class="btn-group btn-group-lg mr-2" role="group" aria-label="First group">
                    <a href="#" role="button" class="btn btn-dark btn-label" aria-disabled="true">Zoom:</a>
                    <select class="btn btn-dark    "
                            name="pagezoom"
                            v-on:change="setZoom"
                            >
                        <option v-for="zoom in zoomLevel"
                            :value="zoom"
                            :key="zoom"
                            :selected="zoom == 70">
                            {{zoom}}
                        </option>
                    </select>
                    <a href="#" role="button" class="btn btn-dark btn-label" aria-disabled="true">Record:</a>
                    <button class="btn btn-dark" aria-disabled="true" @click="leftArrowClicked"><i class="fa fa-arrow-left"></i></button>
                    <a href="#" role="button" class="btn btn-dark" aria-disabled="true" id="current_record" @click="showCurrentRecordInput">1</a>
                    <input type="text" class="btn-light d-none" id="current_record_input" size="3" @change="currentRecordInputChanged($event)">
                    <a href="#" role="button" class="btn btn-dark btn-label" aria-disabled="true">/</a>
                    <a href="#" role="button" class="btn btn-dark btn-label" aria-disabled="true" id="last-record">0</a>
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
                                    <p class="text-right">Θεσσαλονίκη, {{ doc_date }}<br/>
                                                        Αρ. Πρωτ.: {{ protocol_num }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-bottom" id="recipients">
                                    <p class="font-weight-bold mb-0">ΠΡΟΣ</p>
                                    <p>[[ΟΝΟΜΑ]] [[ΕΠΩΝΥΜΟ]]<br/>
                                    ΚΛΑΔΟΥ: [[ΚΛΑΔΟΣ]]<br/>
                                    Α.Μ.: [[ΑΜ]]<br/>
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
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Άκυρο</button>
                            <button type="button" class="btn btn-primary d-none" id="save_recipients" @click="saveRecipientsClicked">Αποθήκευση αντιστοίχισης</button>
                            <a :href="save_mail_merge_url" type="button" class="btn btn-primary disabled" id="save_mail_merge">
                                <div class="spinner-border" role="status">
                                <span class="sr-only">Working...</span>
                                </div>
                            </a>
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
            app_url: String,
        },
        mounted() {
            this.setZoom();
            this.getLastRecord();
            this.showCurrentRecordText();
        },
        data: function() {
            return {
                records: this.setIds(JSON.parse(this.xls_data)),
                current_record: 0,
                recipient_fields: JSON.parse(this.doc_recipient_fields),
                progress: 0,
            }
        },
        watch: {
        },
        methods: {
            setZoom: function() {
                var transformOrigin = [0,0];
                var zoom = $('select[name="pagezoom"').val();
                var el = $('div.page');
                var p = ["webkit", "moz", "ms", "o"],
                    s = "scale(" + zoom/100 + ")",
                    oString = (transformOrigin[0] * 100) + "% " + (transformOrigin[1] * 100) + "%";

                for (var i = 0; i < p.length; i++) {
                    el.css(p[i] + "Transform", s);
                    el.css(p[i] + "TransformOrigin", oString);
                }

                el.css("transform", s);
                el.css("transformOrigin", oString);
            },

            showVal: function(a){
                var zoomScale = Number(a)/10;
                setZoom(zoomScale,document.getElementsByClassName('container')[0])
            },

            setIds: function(data) {
                var i = 1;
                data.forEach(el => {
                    el.id = i;
                    i++;
                });

                return data;
            },

            getLastRecord: function() {
                $('#last-record').html(this.records[this.records.length - 1].id);
            },

            currentRecordChanged: function(e) {
                this.current_record = e.target.value;
                this.showCurrentRecordText();
            },

            replaceFields: function(text) {
                var pattern = /\[\[.+?\]\]/g;
                var matches = [];
                var result;

                // Βρες όλες τις ετικέτες
                while((result = pattern.exec(text)) !== null) {
                    matches.push(result[0]);
                };

                // Για κάθε ετικέτα κάνε αντικατάσταση με την αντίστοιχη τιμή
                var vueobj = this;
                matches.forEach(function(match) {
                    var field = match.slice(2, match.length - 2);
                    if (typeof vueobj.records[vueobj.current_record][field] !== 'undefined') {
                        text = text.replaceAll(match, vueobj.records[vueobj.current_record][field]);
                    }
                    else {
                        text = text.replaceAll(match, '');
                    }
                });
                return text;
            },

            showCurrentRecordText: function() {
                var text = this.doc_text;
                text = this.replaceFields(text);
                $('#doc_text').html(text);

                text = $('#recipients').html();
                text = this.replaceFields(text);
                $('#recipients').html(text);

                var i = 2;
                var recipient_list = "";
                var recipient_list_array = [];
                var vueobj = this;
                this.recipient_fields.forEach(function(field) {
                    if (vueobj.records[vueobj.current_record][field] != undefined &&
                        vueobj.records[vueobj.current_record][field] != "" &&
                        (!recipient_list_array.includes(vueobj.records[vueobj.current_record][field]))) {
                            recipient_list += i+". "+vueobj.records[vueobj.current_record][field]+"<br/>";
                            recipient_list_array.push(vueobj.records[vueobj.current_record][field]);
                            i += 1;
                    }
                });
                $('#recipient-list').html(recipient_list);
            },

            showCurrentRecordInput: function() {
                $('#current_record').addClass('d-none');
                $('#current_record_input').removeClass('d-none');
                $('#current_record_input').focus();
            },

            currentRecordInputChanged: function(e) {
                var cur = e.target.value;
                if (cur < 0 || cur > this.records[this.records.length - 1].id) {
                    $('#current_record').removeClass('d-none');
                    $('#current_record_input').addClass('d-none');
                }
                else {
                    $('#current_record').removeClass('d-none');
                    $('#current_record_input').addClass('d-none');
                    $('#current_record').html(cur);
                    this.current_record = cur - 1;
                    this.showCurrentRecordText();
                }
            },

            leftArrowClicked: function() {
                $('#current_record').removeClass('d-none');
                $('#current_record_input').addClass('d-none');
                if (this.current_record > 0) {
                    this.current_record--;
                    $('#current_record').html(this.current_record + 1);
                    $('#current_record_input').val(this.current_record + 1);
                    this.showCurrentRecordText();
                }
            },

            rightArrowClicked: function() {
                $('#current_record').removeClass('d-none');
                $('#current_record_input').addClass('d-none');
                if (this.current_record < (this.records[this.records.length - 1].id - 1)) {
                    this.current_record++;
                    $('#current_record').html(this.current_record + 1);
                    $('#current_record_input').val(this.current_record + 1);
                    this.showCurrentRecordText();
                }
            },
            saveMailMergeClicked: function() {
                // Αρχικοποίησε τιμές
                this.progress = 0;
                $('#unknown_recipients').addClass('d-none');
                $('#unknown_recipients table tbody tr').remove();

                // Εμφάνισε το modal
                $('#myModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    focus: true,
                    show: true
                });

                var recipients = [];
                var vueobj = this;
                var unknown = 0;

                $.get(this.recipient_list_url, function() {
                })
                    .done(function(data) {
                        recipients = data;
                        var max = vueobj.records[vueobj.records.length - 1].id;
                        console.log(max);
                        console.log(recipients);
                        console.log(vueobj.doc_recipient_fields);

                        // Φτιάξε το combobox με τους διαθέσιμους παραλήπτες
                        var recipient_options = "";
                        recipients.forEach(function (recipient) {
                            recipient_options += "<option value=\"" + recipient.code + "\">" + recipient.name + "</option>";
                        });
                        var recipient_selector = "<select name='recipient'>" + recipient_options + "</select>";

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
                                "name",
                            ]
                        };
                        const fuse = new Fuse.default(recipients, options);


                        //return fuse.search(pattern)

                        vueobj.delayedLoop(vueobj.records, 200, function(item, index) {
                            //console.log(item);
                            //console.log(vueobj.doc_recipient_fields);
                            var doc_fields = JSON.parse(vueobj.doc_recipient_fields);
                            doc_fields.forEach(function (field) {
                                // Κοιτάει για την τιμή του πεδίου στο όνομα του παραλήπτη
                                if ((item[field] != "") &&
                                    !(recipients.map((x) => x.name).includes(item[field]))) {
                                    $("#save_recipients").removeClass("d-none");
                                    unknown++;

                                    // Fuzzy search
                                    const pattern = item[field];
                                    var results = fuse.search(pattern);
                                    var my_recipient_selector;
                                    var color = "";
                                    var icon = "";
                                    if (results.length) {
                                        my_recipient_selector = recipient_selector
                                            .replace('>'+results[0].item.name,
                                                     'selected="selected">'+results[0].item.name);

                                        // Σημείωσε με χρώμα τα σκορ στον πίνακα
                                        if (results[0].score < 0.2) {
                                            color = 'class="bg-success"';
                                        }
                                        else if (results[0].score < 0.5) {
                                            color = 'class="bg-warning"';
                                        }
                                        else {
                                            color = 'class="bg-danger"';
                                        }

                                        // Έλεγχος αριθμών
                                        var num1 = item[field].slice(0,5).match(/\d+/g);
                                        var num2 = results[0].item.name.slice(0,5).match(/\d+/g);
                                        //console.log(num1 + ' <> ' + num2);
                                        num1 = num1 == null ? 0 : num1;
                                        num2 = num2 == null ? 0 : num2;
                                        var percentage = Math.round((1 - parseFloat(results[0].score)) * 10000) / 100;
                                        if (parseInt(num1) == parseInt(num2)) {
                                            icon = '<i>' + percentage + '%</i>';
                                        }
                                        else {
                                            icon = '<i class="fas fa-exclamation-triangle">' +
                                                percentage + '%</i>';
                                        }
                                    }
                                    else {
                                        icon = '<i>0%</i>'
                                        my_recipient_selector = recipient_selector;
                                    }

                                    $('#unknown_recipients').removeClass('d-none');
                                    if ($('#unknown_recipients table tbody').html() == "") {
                                        $('#unknown_recipients table tbody').html('<tr '+color+'><td>'+item[field]+'</td><td>'+icon+'</td><td>'+my_recipient_selector+'</td></tr>');
                                    }
                                    else {
                                        $('#unknown_recipients table tr:last').after('<tr '+color+'><td>'+item[field]+'</td><td>'+icon+'</td><td>'+my_recipient_selector+'</td></tr>');
                                    }
                                }
                            });
                            vueobj.update_progress(index + 1, max);
                            if ((index + 1) == max) {
                                $("#save_mail_merge").html("Λήψη");
                                if (unknown == 0) {
                                    $("#save_mail_merge").removeClass("disabled");
                                }
                                vueobj.sort_table();
                            }
                        });
                    })
                    .fail(function(data) {
                        $('#error_msg').html('Error retrieving recipient list!');
                        $('#error_msg').addClass('alert');
                        $('#error_msg').addClass('alert-danger');
                    });

            },
            sort_table: function() {
                const children = $("#unknown_recipients table tbody").children().get();
                children.sort(function(a, b) {
                    return (parseFloat(b.children[1].innerText.slice(0, -1)) -
                            parseFloat(a.children[1].innerText.slice(0, -1)));
                });
                $("#unknown_recipients table tbody").append(children);
            },
            update_progress: function(value, max) {
                this.progress = Math.round(((parseInt(value) / parseInt(max) * 100) + Number.EPSILON) * 100) / 100;
            },
            check_record_recipients: function(record, recipients) {
                for (var i = 0; i < 100; i++);
            },
            delayedLoop: function(collection, delay, callback, context) {
                context = context || null;

                var i = 0,
                    nextInteration = function() {
                        if (i === collection.length) {
                            return;
                        }

                        callback.call(context, collection[i], i);
                        i++;
                        setTimeout(nextInteration, delay);
                    };

                nextInteration();
            },
            saveRecipientsClicked: function() {
                $("#save_recipients").addClass("disabled");
                var data = new Array();
                $("#unknown_recipients table tr").each(function (index, row) {
                    data.push({name: row.children[0].innerText,
                               code: row.children[2].children[0].selectedOptions[0].value,
                               link: row.children[2].children[0].selectedOptions[0].innerText});
                });
                console.log(data);
                $.post({url: this.store_many_url,
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {many: data}})
                    .done(function (data) {
                        $("#save_mail_merge").removeClass("disabled");
                    })
                    .fail(function (data) {
                        alert("Failed saving data");
                        $("#save_recipients").removeClass("disabled");
                    });
            },
        },
        computed: {
            zoomLevel: function() {
                var lvl = [];
                for (var i=10; i<=100; i+=10) {
                    lvl.push(i);
                }
                return lvl;
            },
            logo_img: function() {
                return this.app_url+"/images/"+this.doc_logo_image;
            },
            doc_logo_text_html: function() {
                return this.doc_logo_text.replace(/\n/g,'<br/>');
            },
            /*locale_date: function() {
                var date = new Date(this.doc_date);
                return date.toLocaleDateString();
            },*/
            exact_copy_html: function() {
                return this.exact_copy_text.replace(/\n/g,'<br/>');
            },
            signature_html: function() {
                return this.signature_text.replace(/\n/g,'<br/>');
            },
            progress_style: function() {
                return "width: "+this.progress+"%;";
            },
            print_url_draft: function() {
                return this.print_url+"?draft=true";
            },
        },
    }
</script>
