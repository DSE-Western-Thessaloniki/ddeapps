<template>
    <div class="card">
        <div class="card-header">{{ __("Roles") }}</div>
        <div class="card body">
            <fieldset class="form-group">
                <div class="row no-gutters p-4">
                    <legend class="col-form-label col-2 pt-0">
                        {{ __("Main Roles") }}:
                    </legend>
                    <div class="col-10">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="Administrator" id="Administrator"
                                value="1" v-model="administrator" />
                            <label for="Administrator" class="form-check-label">{{ __("Administrator") }}</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="User" id="User" value="1"
                                v-model="user" />
                            <label for="user" class="form-check-label">{{
                                    __("User")
                            }}</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="form-group">
                <div class="row no-gutters p-4">
                    <legend class="col-form-label col-4 pt-0">
                        {{ __("Mail Merge") }}:
                    </legend>
                    <div class="col-8">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="MailMergeAdmin" id="MailMergeAdmin"
                                value="1" v-model="mmAdmin" />
                            <label for="MailMergeAdmin" class="form-check-label">{{ __("Administrator") }}</label>
                        </div>
                        <div id="mmdetailroles" class="bg-info" v-if="!mmAdmin">
                            <div class="row no-gutters">
                                <span class="pr-2 col-auto">{{ __("Logos") }}</span>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="DocLogoRead" id="DocLogoRead"
                                        value="1" v-model="mmDocLogoRead" />
                                    <label for="DocLogoRead" class="form-check-label pr-1">{{ __("Read") }}</label>
                                </div>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="DocLogoWrite"
                                        id="DocLogoWrite" value="1" v-model="mmDocLogoWrite" />
                                    <label for="DocLogoWrite" class="form-check-label">{{ __("Write") }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2 col-auto">{{ __("Addresses") }}</span>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="EditorRead" id="EditorRead"
                                        value="1" v-model="mmEditorRead" />
                                    <label for="EditorRead" class="form-check-label pr-1">{{ __("Read") }}</label>
                                </div>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="EditorWrite" id="EditorWrite"
                                        value="1" v-model="mmEditorWrite" />
                                    <label for="EditorWrite" class="form-check-label">{{ __("Write") }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2 col-auto">{{
                                        __("Exact Copies")
                                }}</span>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="ExactCopyRead"
                                        id="ExactCopyRead" value="1" v-model="mmExactCopyRead" />
                                    <label for="ExactCopyRead" class="form-check-label pr-1">{{ __("Read") }}</label>
                                </div>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="ExactCopyWrite"
                                        id="ExactCopyWrite" value="1" v-model="mmExactCopyWrite" />
                                    <label for="ExactCopyWrite" class="form-check-label">{{ __("Write") }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2 col-auto">{{ __("Signatures") }}</span>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="SignatureRead"
                                        id="SignatureRead" value="1" v-model="mmSignatureRead" />
                                    <label for="SignatureRead" class="form-check-label pr-1">{{ __("Read") }}</label>
                                </div>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="SignatureWrite"
                                        id="SignatureWrite" value="1" v-model="mmSignatureWrite" />
                                    <label for="SignatureWrite" class="form-check-label">{{ __("Write") }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2 col-auto">{{ __("Recipients") }}</span>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="RecipientRead"
                                        id="RecipientRead" value="1" v-model="mmRecipientRead" />
                                    <label for="RecipientRead" class="form-check-label pr-1">{{ __("Read") }}</label>
                                </div>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="RecipientWrite"
                                        id="RecipientWrite" value="1" v-model="mmRecipientWrite" />
                                    <label for="RecipientWrite" class="form-check-label">{{ __("Write") }}</label>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <span class="pr-2 col-auto">{{ __("Mail Merge") }}</span>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="MailMergeRead"
                                        id="MailMergeRead" value="1" v-model="mmRead" />
                                    <label for="MailMergeRead" class="form-check-label pr-1">{{ __("Read") }}</label>
                                </div>
                                <div class="col-auto">
                                    <input type="checkbox" class="form-check-input" name="MailMergeWrite"
                                        id="MailMergeWrite" value="1" v-model="mmWrite" />
                                    <label for="MailMergeWrite" class="form-check-label">{{ __("Write") }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import __ from "../../trans";

const props = defineProps<{
    current_roles: string,
}>();

const cur_roles = ref("");
const administrator = ref(false);
const user = ref(false);
const mmAdmin = ref(false);
const mmDocLogoRead = ref(false);
const mmDocLogoWrite = ref(false);
const mmEditorRead = ref(false);
const mmEditorWrite = ref(false);
const mmExactCopyRead = ref(false);
const mmExactCopyWrite = ref(false);
const mmSignatureRead = ref(false);
const mmSignatureWrite = ref(false);
const mmRecipientRead = ref(false);
const mmRecipientWrite = ref(false);
const mmRead = ref(false);
const mmWrite = ref(false);

onMounted(() => {
    cur_roles.value = JSON.parse(props.current_roles);
    administrator.value = cur_roles.value.includes("Administrator");
    user.value = cur_roles.value.includes("User");
    mmAdmin.value = cur_roles.value.includes("MailMergeAdmin");
    mmDocLogoRead.value = cur_roles.value.includes("DocLogoRead");
    mmDocLogoWrite.value = cur_roles.value.includes("DocLogoWrite");
    mmEditorRead.value = cur_roles.value.includes("EditorRead");
    mmEditorWrite.value = cur_roles.value.includes("EditorWrite");
    mmExactCopyRead.value = cur_roles.value.includes("ExactCopyRead");
    mmExactCopyWrite.value = cur_roles.value.includes("ExactCopyWrite");
    mmSignatureRead.value = cur_roles.value.includes("SignatureRead");
    mmSignatureWrite.value = cur_roles.value.includes("SignatureWrite");
    mmRecipientRead.value = cur_roles.value.includes("RecipientRead");
    mmRecipientWrite.value = cur_roles.value.includes("RecipientWrite");
    mmRead.value = cur_roles.value.includes("MailMergeRead");
    mmWrite.value = cur_roles.value.includes("MailMergeWrite");
});
</script>
