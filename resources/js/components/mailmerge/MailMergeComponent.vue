<template>
    <div class="container">

        <!-- Check if something is missing -->
        <div v-if="doc_logos.length == 0">
            <div class="alert alert-danger">
                <ul>
                <li>{{ __('Cannot continue without creating a logo') }}</li>
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
                <div class="form-group">
                    <label for="logoselect">{{ __('Logo:') }}</label>
                    <select class="form-control" id="logoselect" name="logoselect">
                        <option v-for="doc_logo in doc_logos"
                                :key="doc_logo.id"
                                :value="doc_logo.id"
                        >
                            {{ doc_logo.title }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="addressselect">{{ __('Address:') }}</label>
                    <select class="form-control" id="addressselect" name="addressselect">
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
                            <label for="protocol">{{ __('Protocol number:') }}</label>
                            <input type="text" id="protocol" name="protocol" class="form-control" required>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="date">{{ __('Date:') }}</label>
                            <input type="date" id="date" name="date" :value="new Date().toLocaleDateString('en-CA')" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="step == 2">
                <div class="form-group">
                    <label for="subject">{{ __('Subject:') }}</label>
                    <textarea id="subject" name="subject" class="form-control">
                    </textarea>
                </div>
                <div class="form-group">
                    <label for="text">{{ __('Text:') }}</label>
                    <textarea id="text" name="text" class="form-control" rows="10">
                    </textarea>
                </div>
            </div>

            <div v-show="step == 3">
                <div class="form-group">
                    <label for="exactcopyselect">{{ __('Exact Copy:') }}</label>
                    <select class="form-control" id="exactcopyselect" name="exactcopyselect">
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
                    <select class="form-control" id="signatureselect" name="signatureselect">
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
                <i v-show="step != 1" class="fas fa-fw fa-square"></i>
                <i v-show="step == 1" class="far fa-fw fa-square"></i>
                <i v-show="step != 2" class="fas fa-fw fa-square"></i>
                <i v-show="step == 2" class="far fa-fw fa-square"></i>
                <i v-show="step != 3" class="fas fa-fw fa-square"></i>
                <i v-show="step == 3" class="far fa-fw fa-square"></i>
                <a class="fas fa-fw fa-arrow-right" v-show="step < 3" v-on:click="step += 1" href="#"></a>
            </div>

            <br/>
            <div class="form-group row">
                <div class="col-1">
                    <a class="btn btn-danger" :href="route_index">{{ __('Cancel') }}</a>
                </div>
                <div class="col-10"></div>
                <div v-show="step == 3" class="col-1">
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            doc_logos_str: String,
            doc_addresses_str: String,
            signatures_str: String,
            exact_copies_str: String,
            route_exact_copy_create: String,
            route_doc_logo_create: String,
            route_signature_create: String,
            route_doc_address_create: String,
            route_index: String,
        },
        mounted() {
            console.log('MailMerge mounted.')
        },
        data: function() {
            return {
                step: 1,
            }
        },
        methods: {
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
            }
        }
    }
</script>
