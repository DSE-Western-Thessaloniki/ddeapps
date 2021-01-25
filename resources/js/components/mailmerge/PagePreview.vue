<template>
    <div class="container"> <!-- Page preview -->
        <div class="btn-toolbar"> <!-- toolbar -->
            <div class="btn-toolbar" role="toolbar" aria-label="Preview toolbar">
                <div class="btn-group mr-2" role="group" aria-label="First group">
                    <a href="#" role="button" class="btn btn-dark btn-label" aria-disabled="true">Zoom:</a>
                    <select class="btn btn-dark    "
                            name="pagezoom"
                            v-on:change="setZoom"
                            >
                        <option v-for="zoom in zoomLevel"
                            :value="zoom"
                            :key="zoom"
                            :selected="zoom == 50">
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
                    <button class="btn btn-dark" aria-disabled="true" @click="printClicked"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>
        <div class="page" size="A4">
            <div class="row">
                <div class="col-6">
                    <p class="text-center"><img :src="logo_img"></p>
                    <p class="text-center" v-html="doc_logo_text_html"></p>
                    <table class="table table-borderless doc-address-col">
                        <tr>
                            <td class="no-wrap pr-1">Ταχ. Διεύθυνση:</td>
                            <td>{{ doc_address_address }}</td>
                        </tr>
                        <tr>
                            <td>Πληροφορίες:</td>
                            <td>{{ doc_address_name }}</td>
                        </tr>
                        <tr>
                            <td>Τηλέφωνο:</td>
                            <td>{{ doc_address_telephone }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>{{ doc_address_email }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-borderless doc-recipient-col">
                        <tr>
                            <td>
                                <p class="text-right">Θεσσαλονίκη, {{ locale_date }}<br/>
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

                </div>
            </div>

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
    </div>
</template>

<script>
    export default {
        props: {
            doc_address_address: String,
            doc_address_name: String,
            doc_address_telephone: String,
            doc_address_email: String,
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
        },
        mounted() {
            console.log('Pagepreview mounted.');
            console.log(this.xls_data)
            console.log(this.records)
            this.setZoom();
            this.getLastRecord();
            this.showCurrentRecordText();
        },
        data: function() {
            return {
                records: this.setIds(JSON.parse(this.xls_data)),
                current_record: 0,
                recipient_fields: JSON.parse(this.doc_recipient_fields),
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
                var vueobj = this;
                this.recipient_fields.forEach(function(field) {
                    recipient_list += i+". "+vueobj.records[vueobj.current_record][field]+"<br/>";
                    i += 1;
                });
                console.log(recipient_list);
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
            printClicked: function() {
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
                return "/public/images/"+this.doc_logo_image;
            },
            doc_logo_text_html: function() {
                return this.doc_logo_text.replace(/\n/g,'<br/>');
            },
            locale_date: function() {
                var date = new Date(this.doc_date);
                return date.toLocaleDateString();
            },
            exact_copy_html: function() {
                return this.exact_copy_text.replace(/\n/g,'<br/>');
            },
            signature_html: function() {
                return this.signature_text.replace(/\n/g,'<br/>');
            },
        },
    }
</script>
