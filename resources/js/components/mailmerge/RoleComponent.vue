<template>
    <div class="card">
        <div class="card-header">{{ __('Roles') }}</div>
        <div class="card body">
            <fieldset class="form-group">
                <div class="row no-gutters p-4">
                    <legend class="col-form-label col-2 pt-0">{{ __('Main Roles') }}:</legend>
                    <div class="col-10">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="Administrator" id="Administrator" value="1" v-model="administrator">
                            <label for="Administrator" class="form-check-label">{{ __('Administrator') }}</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="User" id="User" value="1" v-model="user">
                            <label for="user" class="form-check-label">{{ __('User') }}</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group">
                <div class="row no-gutters p-4">
                    <legend class="col-form-label col-4 pt-0">{{ __('Mail Merge') }}:</legend>
                    <div class="col-8">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="MailMergeAdmin" id="MailMergeAdmin" value="1" v-model="mmadmin">
                            <label for="MailMergeAdmin" class="form-check-label">{{ __('Administrator') }}</label>
                        </div>
                        <div id="mmdetailroles" class="bg-info" v-if="!mmadmin">
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Logos') }}</span>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="DocLogoRead" id="DocLogoRead" value="1" v-model="mmdoclogoread">
                                    <label for="DocLogoRead" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check-inline">
                                    <input type="checkbox" class="form-check-input" name="DocLogoWrite" id="DocLogoWrite" value="1" v-model="mmdoclogowrite">
                                    <label for="DocLogoWrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Addresses') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="EditorRead" id="EditorRead" value="1" v-model="mmeditorread">
                                    <label for="EditorRead" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="EditorWrite" id="EditorWrite" value="1" v-model="mmeditorwrite">
                                    <label for="EditorWrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Exact Copies') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="ExactCopyRead" id="ExactCopyRead" value="1" v-model="mmexactcopyread">
                                    <label for="ExactCopyRead" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="ExactCopyWrite" id="ExactCopyWrite" value="1" v-model="mmexactcopywrite">
                                    <label for="ExactCopyWrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Signatures') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="SignatureRead" id="SignatureRead" value="1" v-model="mmsignatureread">
                                    <label for="SignatureRead" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="SignatureWrite" id="SignatureWrite" value="1" v-model="mmsignaturewrite">
                                    <label for="SignatureWrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Recipients') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="RecipientRead" id="RecipientRead" value="1" v-model="mmrecipientread">
                                    <label for="RecipientRead" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="RecipientWrite" id="RecipientWrite" value="1" v-model="mmrecipientwrite">
                                    <label for="RecipientWrite" class="form-check-label">{{ __('Write') }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2">{{ __('Mail Merge') }}</span>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="MailMergeRead" id="MailMergeRead" value="1" v-model="mmread">
                                    <label for="MailMergeRead" class="form-check-label pr-1">{{ __('Read') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="MailMergeWrite" id="MailMergeWrite" value="1" v-model="mmwrite">
                                    <label for="MailMergeWrite" class="form-check-label">{{ __('Write') }}</label>
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
            this.mmeditorread=this.cur_roles.includes('EditorRead');
            this.mmeditorwrite=this.cur_roles.includes('EditorWrite');
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
                mmeditorread: false,
                mmeditorwrite: false,
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
