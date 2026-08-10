<script setup lang="ts">
import { reactive, watch, computed } from "vue";

const props = defineProps<{
    progressStyle: string;
    progress: number;
    errorMessage: string;
    unknownRecipients: Array<{
        color: string;
        name: string;
        icon: string;
        percentage: number;
    }>;
    urOptions: Array<{
        name: string;
        value: string;
    }>;
    urSelected: Record<string, string>;
    saveMailMergeReady: boolean;
    saveMailMergeLoading: boolean;
    saveRecipientsLoading: boolean;
}>();

const emit = defineEmits<{
    (e: "saveRecipientsClicked"): void;
    (e: "readySaveMailMergeClicked"): void;
    (
        e: "recipientSelectorChanged",
        payload: { name: string; value: string },
    ): void;
}>();

const saveRecipientsDisabled = computed(
    () => props.unknownRecipients.length === 0 || props.saveRecipientsLoading,
);

const saveMailMergeDisabled = computed(
    () => !props.saveMailMergeReady || props.saveMailMergeLoading,
);

const selectedValues = reactive<Record<string, string>>({});

watch(
    () => props.urSelected,
    (value) => {
        Object.keys(selectedValues).forEach((key) => {
            delete selectedValues[key];
        });

        Object.assign(selectedValues, value || {});
    },
    { immediate: true, deep: true },
);

const onRecipientChange = (name: string, event: Event) => {
    if (event.target instanceof HTMLSelectElement) {
        const value = event.target.value;
        selectedValues[name] = value;
        emit("recipientSelectorChanged", { name, value });
    }
};
</script>

<template>
    <div class="modal" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Έλεγχος αποδεκτών αλληλογραφίας</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    />
                </div>
                <div class="modal-body">
                    <p>
                        Γίνεται έλεγχος των αποδεκτών της αλληλογραφίας σας.
                        Μόλις ολοκληρωθεί ο έλεγχος θα ενεργοποιηθεί το κουμπί
                        της λήψης.
                    </p>
                    <div class="progress">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            :style="progressStyle"
                            :aria-valuenow="progress"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        >
                            {{ progress }}%
                        </div>
                    </div>
                    <div
                        v-if="errorMessage"
                        id="error_msg"
                        class="alert alert-danger"
                    >
                        {{ errorMessage }}
                    </div>
                    <br />
                    <div
                        v-if="unknownRecipients.length"
                        id="unknown_recipients"
                    >
                        <p>
                            Οι παρακάτω παραλήπτες δεν βρέθηκαν στο σύστημα για
                            αντιστοίχιση με κωδικό σχολικής μονάδας. Παρακαλούμε
                            επιλέξτε από δίπλα αν η σχολική μονάδα εμφανίζεται
                            με άλλο όνομα.
                        </p>
                        <table class="table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Όνομα</th>
                                    <th>Ποσοστό ταιριάσματος</th>
                                    <th>Αντιστοίχιση</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="unknownRecipient in unknownRecipients"
                                    :key="unknownRecipient.name"
                                    :class="unknownRecipient.color"
                                >
                                    <td>{{ unknownRecipient.name }}</td>
                                    <td>
                                        <i
                                            v-if="unknownRecipient.icon"
                                            :class="unknownRecipient.icon"
                                        ></i>
                                        {{ unknownRecipient.percentage }}%
                                    </td>
                                    <td>
                                        <select
                                            name="recipient"
                                            @change="
                                                onRecipientChange(
                                                    unknownRecipient.name,
                                                    $event,
                                                )
                                            "
                                            v-model="
                                                selectedValues[
                                                    unknownRecipient.name
                                                ]
                                            "
                                        >
                                            <option
                                                v-for="urOption in urOptions"
                                                :key="urOption.value"
                                                :value="urOption.value"
                                            >
                                                {{ urOption.name }}
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Άκυρο
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            id="save_recipients"
                            @click="$emit('saveRecipientsClicked')"
                            :disabled="saveRecipientsDisabled"
                        >
                            <span
                                v-if="saveRecipientsLoading"
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            <span v-if="saveRecipientsLoading">
                                Αποθήκευση...</span
                            >
                            <span v-else>Αποθήκευση αντιστοίχισης</span>
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            id="save_mail_merge"
                            @click="$emit('readySaveMailMergeClicked')"
                            :disabled="saveMailMergeDisabled"
                        >
                            <span
                                v-if="saveMailMergeLoading"
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            <span v-if="saveMailMergeLoading"> Αναμονή...</span>
                            <span v-else>Λήψη</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
