<template>
    <div class="container"> <!-- Page preview -->
        <div class="btn-toolbar"> <!-- toolbar -->
            <div class="btn-toolbar" role="toolbar" aria-label="Preview toolbar">
                <div class="btn-group mr-2" role="group" aria-label="First group">
                    <a href="#" role="button" class="btn btn-dark" aria-disabled="true">Zoom:</a>
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
                    <a href="#" role="button" class="btn btn-dark" aria-disabled="true">Record:</a>
                    <select class="btn btn-dark" @change="currentRecordChanged($event)">
                        <option v-for="record in records"
                            :value="record.id - 1"
                            :key="record.id"
                        >
                        {{ record.id }}
                        </option>
                    </select>
                    <a href="#" role="button" class="btn btn-dark btn-label" aria-disabled="true" id="last-record">/0</a>
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
                            <td class="align-bottom">
                                <p class="font-weight-bold">ΠΡΟΣ</p>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <p class="font-weight-bold">Θέμα: «{{ doc_subject }}»</p>
            <p id="doc_text"></p>

            <table>
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
                $('#last-record').html('/ ' + this.records[this.records.length - 1].id);
            },

            currentRecordChanged: function(e) {
                this.current_record = e.target.value;
                this.showCurrentRecordText();
            },

            showCurrentRecordText: function() {
                var text = this.doc_text;
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
                    text = text.replaceAll(match, vueobj.records[vueobj.current_record][field]);
                });
                $('#doc_text').html(text);
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
