<template>
    <div class="container">

        <!-- Check if something is missing -->
        <div v-if="doc_logos.length == 0">
            <div class="alert alert-danger">
                <ul>
                <li>{{ __('Cannot continue without creating a logo.') }}</li>
                </ul>
            </div><br />
            <a class="btn btn-primary" :href="route_doc_logo_create">
                <i class="fa-fw fas fa-plus-circle">
                </i> {{ __('Create Logo') }}
            </a>
        </div>
        <div v-else-if="doc_addresses.length == 0">
            <div class="alert alert-danger">
                <ul>
                <li>{{ __('Cannot continue without creating an address.') }}</li>
                </ul>
            </div><br />
            <a class="btn btn-primary" :href="route_doc_address_create">
                <i class="fa-fw fas fa-plus-circle">
                </i> {{ __('Create Address') }}
            </a>
        </div>
        <div v-else-if="signatures.length == 0">
            <div class="alert alert-danger">
                <ul>
                <li>{{ __('Cannot continue without creating a signature.') }}</li>
                </ul>
            </div><br />
            <a class="btn btn-primary" :href="route_signature_create">
                <i class="fa-fw fas fa-plus-circle">
                </i> {{ __('Create Signature') }}
            </a>
        </div>
        <div v-else-if="exact_copies.length == 0">
            <div class="alert alert-danger">
                <ul>
                <li>{{ __('Cannot continue without creating an exact copy.') }}</li>
                </ul>
            </div><br />
            <a class="btn btn-primary" :href="route_exact_copy_create">
                <i class="fa-fw fas fa-plus-circle">
                </i> {{ __('Create Exact Copy') }}
            </a>
        </div>
        <div v-else>

            <!-- All OK, present the form -->
            <div v-show="step == 1">
                <div class="card bg-success">
                    <div class="card-body">
                    <h5 class="class-title">
                        {{ __("Select data source") }}
                    </h5>
                    <div class="class-text">
                        {{ __("You can select one or more columns to be used as a recipient list by right clicking on each column.") }}
                    </div>
                    </div>
                </div>
                <br />
                <xlsxcomponent
                    ref="xlsxcomponent"
                    v-on:setmergefields="setmergefields"
                    :docdata="doc_data"
                    :docdataheader="doc_data_header"
                    :mfields="doc_mfields"
                >
                </xlsxcomponent>
            </div>
            <div v-show="step == 2">
                <div class="form-group">
                    <label for="logoselect">{{ __('Logo')+':' }}</label>
                    <select class="form-control" id="logoselect" name="logoselect" v-model="logo_selected">
                        <option v-for="doc_logo in doc_logos"
                                :key="doc_logo.id"
                                :value="doc_logo.id"
                        >
                            {{ doc_logo.title }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="addressselect">{{ __('Address')+':' }}</label>
                    <select class="form-control" id="addressselect" name="addressselect" v-model="address_selected">
                        <option v-for="doc_address in doc_addresses"
                                :key="doc_address.id"
                                :value="doc_address.id"
                        >
                            {{ doc_address.title }}
                        </option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label for="protocol">{{ __('Protocol number')+':' }}</label>
                            <input type="text" id="protocol" name="protocol" class="form-control" required v-model="prot_num">
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="date">{{ __('Date')+':' }}</label>
                            <input type="date" id="date" name="date" :value="get_date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="step == 3">
                <div class="form-group">
                    <label for="subject">{{ __('Subject')+':' }}</label>
                    <textarea id="subject" name="subject" class="form-control" v-model="subject">
                    </textarea>
                </div>
                <div class="form-group">
                    <label for="text">{{ __('Text')+':' }}</label>
                    <textarea id="text" name="text" class="form-control" rows="10" v-model="editorData" hidden>
                    </textarea>
                    <ckeditor ref="ckeditor" v-model="editorData" :config="editorConfig" @ready="ckEditorReadyCallback"></ckeditor>
                </div>
            </div>

            <div v-show="step == 4">
                <div class="form-group">
                    <label for="exactcopyselect">{{ __('Exact Copy:') }}</label>
                    <select class="form-control" id="exactcopyselect" name="exactcopyselect" v-model="exact_copy_selected">
                        <option v-for="exact_copy in exact_copies"
                                :key="exact_copy.id"
                                :value="exact_copy.id"
                        >
                            {{ exact_copy.title }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="signatureselect">{{ __('Signature:') }}</label>
                    <select class="form-control" id="signatureselect" name="signatureselect" v-model="signature_selected">
                            <option v-for="signature in signatures"
                                    :key="signature.id"
                                    :value="signature.id"
                            >
                                {{ signature.title }}
                            </option>
                    </select>
                </div>
            </div>

            <div class="form-group row justify-content-center h1">
                <a class="fas fa-fw fa-arrow-left" v-show="step > 1" v-on:click="step -= 1" href="#"></a>
                <span v-for="i in steps" :key="i">
                    <i v-show="step != i" class="fas fa-fw fa-square"></i>
                    <i v-show="step == i" class="far fa-fw fa-square"></i>
                </span>
                <a class="fas fa-fw fa-arrow-right" v-show="step < steps" v-on:click="step += 1" href="#"></a>
            </div>

            <br/>
            <div class="form-group row">
                <div class="col-auto mr-auto">
                    <a class="btn btn-danger" :href="route_index">{{ __('Cancel') }}</a>
                </div>
                <div v-show="step == steps" class="col-auto">
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        components: {
            ckeditor: CKEditor_Vue.component
        },
        props: {
            doc_logos_str: String,
            doc_logos_selected: String,
            doc_addresses_str: String,
            doc_addresses_selected: String,
            signatures_str: String,
            signatures_selected: String,
            exact_copies_str: String,
            exact_copies_selected: String,
            protocol_num: String,
            doc_date: String,
            doc_subject: String,
            doc_text: String,
            doc_data: String,
            doc_data_header: String,
            doc_mfields: String,
            route_exact_copy_create: String,
            route_doc_logo_create: String,
            route_signature_create: String,
            route_doc_address_create: String,
            route_index: String,
        },
        created() {
        },
        mounted() {
            console.log('MailMerge mounted.');
        },
        data: function() {
            return {
                step: 1,
                steps: 4,
                mergefields: [],
                editorData: this.doc_text,
                editorConfig: {
                    language: 'el',
                    removePlugins: ['stylescombo'],
                    extraPlugins: ['placeholder_select'],
                    placeholder_select: {
                        placeholders: ['Firstname', 'Lastname', 'Email'],
                    }
                },
                placeholders: [],
                autocomplete: Object,
                prot_num: this.protocol_num,
                subject: this.doc_subject,
                config: {},
                logo_selected: this.doc_logos_selected,
                address_selected: this.doc_addresses_selected,
                signature_selected: this.signatures_selected,
                exact_copy_selected: this.exact_copies_selected,
            };
        },
        methods: {
            setmergefields: function(fields) {
                console.log(fields);
                var new_placeholders = new Array();
                var i = 1;
                fields.forEach(function(field) {
                    new_placeholders.push({id: i, title: field});
                    i++;
                });
                window.itemsArray = new_placeholders;
                this.editorConfig.placeholder_select.placeholders = fields;
                console.log(this.autocomplete);
                CKEDITOR.instances.editor1.config.placeholder_select.placeholders = JSON.parse(JSON.stringify(fields));
                CKEDITOR.instances.editor1.ui.instances.placeholder_select.buildList();
            },

            ckEditorReadyCallback: function(readyEvent) {
                window.itemsArray = this.placeholders;

                function matchCallback(text, offset) {

                    var pattern = /\[{2}([A-zΑ-ω]|\])*$/,
                    match = text.slice(0, offset)
                    .match(pattern);

                    if ( !match ) {
                        return null;
                    }

                    return {
                        start: match.index,
                        end: offset
                    };
                }

                function textTestCallback(range) {

                    if (!range.collapsed) {
                        return null;
                    }

                    return CKEDITOR.plugins.textMatch.match(range, matchCallback);
                }

                this.config.textTestCallback = textTestCallback;

                function dataCallback(matchInfo, callback) {

                    var data = window.itemsArray.filter(function(item) {
                        var itemName = '[[' + item.title + ']]';
                        return itemName.toUpperCase()
                                        .indexOf(matchInfo.query.toUpperCase()) == 0;
                    });

                    callback(data);
                }

                this.config.dataCallback = dataCallback;

                this.config.itemTemplate = '<li data-id="{id}">' +
                '<div><strong class="item-title">{title}</strong></div>' +
                '</li>';
                this.config.outputTemplate = '[[{title}]]<span>&nbsp;</span>';

                this.myAutocomplete(readyEvent, this.config);
                this.$refs.xlsxcomponent.parseDocData();
            },
            myAutocomplete: function(editor, config) {

                this.autocomplete = new CKEDITOR.plugins.autocomplete(editor, config);
                // Override default getHtmlToInsert to enable rich content output.
                /*this.autocomplete.getHtmlToInsert = function(item) {
                    return config.outputTemplate.output(item);
                }*/
            }
        },
        computed: {
            doc_logos: function() {
                return JSON.parse(this.doc_logos_str)
            },
            doc_addresses: function() {
                return JSON.parse(this.doc_addresses_str)
            },
            signatures: function() {
                return JSON.parse(this.signatures_str)
            },
            exact_copies: function() {
                return JSON.parse(this.exact_copies_str)
            },
            get_date: function() {
                if (typeof this.doc_date === 'undefined' || this.doc_date == "") {
                    return new Date().toISOString().slice(0, 10);
                }
                return this.doc_date;
            }
        }
    }
</script>
