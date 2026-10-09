<script setup lang="ts">
const props = defineProps<{
    logoImg: string;
    docLogoTextHtml: string;
    editorAddress: string;
    editorName: string;
    editorTelephone: string;
    editorEmail: string;
    docAda: string;
    docDate: string;
    protocolNum: string;
    docSubject: string;
    docTextHtml: string;
    recipientsHtml: string;
    recipientListHtml: string;
    exactCopyHtml: string;
    signatureHtml: string;
}>();
</script>

<template>
    <div class="page" size="A4">
        <table class="table table-borderless">
            <tr>
                <td class="w-50">
                    <p class="text-center">
                        <img :src="logoImg" width="50" />
                    </p>
                    <p class="text-center" v-html="docLogoTextHtml"></p>
                    <table class="table table-borderless doc-address-col">
                        <tr>
                            <td class="no-wrap pr-1">Ταχ. Διεύθυνση:</td>
                            <td>{{ editorAddress }}</td>
                        </tr>
                        <tr>
                            <td>Πληροφορίες:</td>
                            <td>{{ editorName }}</td>
                        </tr>
                        <tr>
                            <td>Τηλέφωνο:</td>
                            <td>{{ editorTelephone }}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>{{ editorEmail }}</td>
                        </tr>
                    </table>
                </td>
                <td class="w-50">
                    <table class="table table-borderless doc-recipient-col">
                        <tbody>
                            <tr>
                                <td>
                                    <p class="text-right" v-if="docAda">
                                        <b>ΑΔΑ: {{ docAda }}</b>
                                    </p>
                                    <p class="text-right">
                                        Θεσσαλονίκη, {{ docDate }}<br />
                                        Αρ. Πρωτ.: {{ protocolNum }}
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-bottom">
                                    <div v-html="recipientsHtml"></div>
                                    <div v-html="recipientListHtml"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <p class="font-weight-bold" style="text-align: justify">
            Θέμα: «{{ docSubject }}»
        </p>
        <p v-html="docTextHtml"></p>

        <table class="table table-borderless signature-table">
            <tbody>
                <tr>
                    <td class="text-center" v-html="exactCopyHtml"></td>
                    <td class="text-center" v-html="signatureHtml"></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
