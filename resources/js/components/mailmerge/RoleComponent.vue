<template>
    <div class="card">
        <div class="card-header">{{ __('Roles') }}</div>
        <div class="card body">
            <fieldset class="form-group">
                <div class="row no-gutters p-4">
                    <legend class="col-form-label col-2 pt-0">{{ __('Main Roles') }}:</legend>
                    <div class="col-10">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="administrator" id="administrator" value="1" v-model="administrator">
                            <label for="administrator" class="form-check-label">{{ __('Administrator') }}</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="user" id="user" value="1" v-model="user">
                            <label for="user" class="form-check-label">{{ __('User') }}</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group">
                <div class="row no-gutters p-4">
                    <legend class="col-form-label col-4 pt-0">{{ __('Mail Merge') }}:</legend>
                    <div class="col-6">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="mmadmin" id="mmadmin" value="1" v-model="mmadmin">
                            <label for="mmadmin" class="form-check-label">{{ __('Administrator') }}</label>
                        </div>
                        <div id="mmdetailroles" class="bg-info" v-if="!mmadmin">
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Logos') }}</span>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="mmdoclogoread" id="mmdoclogoread" value="1" v-model="mmdoclogoread">
                                    <label for="mmdoclogoread" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="mmdoclogowrite" id="mmdoclogowrite" value="1" v-model="mmdoclogowrite">
                                    <label for="mmdoclogowrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Addresses') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmdocaddressread" id="mmdocaddressread" value="1" v-model="mmdocaddressread">
                                    <label for="mmdocaddressread" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmdocaddresswrite" id="mmdocaddresswrite" value="1" v-model="mmdocaddresswrite">
                                    <label for="mmdocaddresswrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Exact Copies') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmexactcopyread" id="mmexactcopyread" value="1" v-model="mmexactcopyread">
                                    <label for="mmexactcopyread" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmexactcopywrite" id="mmexactcopywrite" value="1" v-model="mmexactcopywrite">
                                    <label for="mmexactcopywrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Signatures') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmsignatureread" id="mmsignatureread" value="1" v-model="mmsignatureread">
                                    <label for="mmsignatureread" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmsignaturewrite" id="mmsignaturewrite" value="1" v-model="mmsignaturewrite">
                                    <label for="mmsignaturewrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Recipients') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmrecipientread" id="mmrecipientread" value="1" v-model="mmrecipientread">
                                    <label for="mmrecipientread" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmrecipientwrite" id="mmrecipientwrite" value="1" v-model="mmrecipientwrite">
                                    <label for="mmrecipientwrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Mail Merge') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmread" id="mmread" value="1" v-model="mmread">
                                    <label for="mmread" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="mmwrite" id="mmwrite" value="1" v-model="mmwrite">
                                    <label for="mmwrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

        </div>
    </div>
</template>

<script>

    export default {
        props: {
            current_roles: String,
        },
        mounted() {
            this.cur_roles=JSON.parse(this.current_roles);
            this.administrator=this.cur_roles.includes('Administrator');
            this.user=this.cur_roles.includes('User');
            this.mmadmin=this.cur_roles.includes('MailMergeAdmin');
            this.mmdoclogoread=this.cur_roles.includes('DocLogoRead');
            this.mmdoclogowrite=this.cur_roles.includes('DocLogoWrite');
            this.mmdocaddressread=this.cur_roles.includes('DocAddressRead');
            this.mmdocaddresswrite=this.cur_roles.includes('DocAddressWrite');
            this.mmexactcopyread=this.cur_roles.includes('ExactCopyRead');
            this.mmexactcopywrite=this.cur_roles.includes('ExactCopyWrite');
            this.mmsignatureread=this.cur_roles.includes('SignatureRead');
            this.mmsignaturewrite=this.cur_roles.includes('SignatureWrite');
            this.mmrecipientread=this.cur_roles.includes('RecipientRead');
            this.mmrecipientwrite=this.cur_roles.includes('RecipientWrite');
            this.mmread=this.cur_roles.includes('MailMergeRead');
            this.mmwrite=this.cur_roles.includes('MailMergeWrite');
        },
        data: function() {
            return {
                cur_roles: '',
                administrator: false,
                user: false,
                mmadmin: false,
                mmdoclogoread: false,
                mmdoclogowrite: false,
                mmdocaddressread: false,
                mmdocaddresswrite: false,
                mmexactcopyread: false,
                mmexactcopywrite: false,
                mmsignatureread: false,
                mmsignaturewrite: false,
                mmrecipientread: false,
                mmrecipientwrite: false,
                mmread: false,
                mmwrite: false,
            }
        },
        ready: function() {
        },
        methods: {
        },
        computed: {
        }
    }
</script>
