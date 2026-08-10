<script setup lang="ts">
import { ref, watch, nextTick } from "vue";
import __ from "../../trans";

const props = defineProps<{
    zoomLevels: string[];
    currentRecord: number;
    lastRecord: number;
    editMailmergeUrl: string;
    printUrlDraft: string;
    printUrl: string;
    uploadUrl: string;
    signedUrl: string;
}>();

const emit = defineEmits<{
    (e: "setZoom", event: Event): void;
    (e: "leftArrowClicked"): void;
    (e: "rightArrowClicked"): void;
    (e: "changeRecord", value: number): void;
    (e: "saveMailMergeClicked"): void;
}>();

const showInput = ref(false);
const inputValue = ref(String(props.currentRecord));

watch(
    () => props.currentRecord,
    (value) => {
        inputValue.value = String(value);
    },
);

const onZoomChange = (event: Event) => {
    emit("setZoom", event);
};

const toggleInput = async () => {
    showInput.value = true;
    await nextTick();
    const element = document.getElementById("current_record_input");
    if (element instanceof HTMLInputElement) {
        element.focus();
    }
};

const applyInput = () => {
    const value = parseInt(inputValue.value, 10);
    if (!Number.isNaN(value)) {
        emit("changeRecord", value);
    }
    showInput.value = false;
};
</script>

<template>
    <div class="btn-toolbar">
        <div
            class="btn-toolbar mb-3"
            role="toolbar"
            aria-label="Preview toolbar"
        >
            <div
                class="btn-group btn-group-lg mr-2"
                role="group"
                aria-label="First group"
            >
                <button
                    role="button"
                    class="btn btn-dark btn-label"
                    aria-disabled="true"
                >
                    <span class="align-middle">{{ __("Zoom") }}:</span>
                </button>
                <select
                    class="btn btn-dark"
                    name="pagezoom"
                    @change="onZoomChange"
                >
                    <option
                        v-for="zoom in zoomLevels"
                        :value="zoom"
                        :key="zoom"
                        :selected="zoom === '70%'"
                    >
                        {{ zoom }}
                    </option>
                </select>
                <button
                    role="button"
                    class="btn btn-dark btn-label"
                    aria-disabled="true"
                >
                    <span class="align-middle">{{ __("Record") }}:</span>
                </button>
                <button
                    class="btn btn-dark"
                    aria-disabled="true"
                    @click="$emit('leftArrowClicked')"
                >
                    <i class="fa fa-arrow-left"></i>
                </button>
                <template v-if="!showInput">
                    <button
                        role="button"
                        class="btn btn-dark"
                        aria-disabled="true"
                        id="current_record"
                        @click="toggleInput"
                    >
                        <span class="align-middle">{{ currentRecord }}</span>
                    </button>
                </template>
                <template v-else>
                    <input
                        type="text"
                        class="btn-light"
                        id="current_record_input"
                        size="3"
                        v-model="inputValue"
                        @blur="applyInput"
                        @keyup.enter="applyInput"
                    />
                </template>
                <button
                    role="button"
                    class="btn btn-dark btn-label"
                    aria-disabled="true"
                >
                    <span class="align-middle">/</span>
                </button>
                <button
                    role="button"
                    class="btn btn-dark btn-label"
                    aria-disabled="true"
                    id="last-record"
                >
                    <span class="align-middle">{{ lastRecord }}</span>
                </button>
                <button
                    class="btn btn-dark"
                    aria-disabled="true"
                    @click="$emit('rightArrowClicked')"
                >
                    <i class="fa fa-arrow-right"></i>
                </button>
                <a
                    :href="editMailmergeUrl"
                    class="btn btn-dark preview-toolbar-button"
                    aria-disabled="true"
                    data-bs-toggle="tooltip"
                    data-placement="bottom"
                    title="Επεξεργασία εγγράφου"
                >
                    <i class="fas fa-pencil-alt"></i><br />
                    <span>Επεξεργασία</span>
                </a>
                <a
                    :href="printUrlDraft"
                    target="_blank"
                    class="btn btn-dark preview-toolbar-button"
                    aria-disabled="true"
                    data-bs-toggle="tooltip"
                    data-placement="bottom"
                    title="Εκτύπωση συγχωνευμένων εγγράφων (τρίπτυχο)"
                >
                    <i class="fab fa-firstdraft"></i><br />
                    <span>Τρίπτυχο</span>
                </a>
                <a
                    :href="printUrl"
                    target="_blank"
                    class="btn btn-dark preview-toolbar-button"
                    aria-disabled="true"
                    data-bs-toggle="tooltip"
                    data-placement="bottom"
                    title="Εκτύπωση συγχωνευμένων εγγράφων"
                >
                    <i class="fas fa-print"></i><br />
                    <span>Εκτύπωση</span>
                </a>
                <button
                    class="btn btn-dark preview-toolbar-button"
                    aria-disabled="true"
                    @click="$emit('saveMailMergeClicked')"
                    data-bs-toggle="tooltip"
                    data-placement="bottom"
                    title="Αποθήκευση συγχωνευμένων εγγράφων"
                >
                    <i class="fas fa-mail-bulk"></i><br />
                    <span>Αποθήκευση</span>
                </button>
                <a
                    :href="uploadUrl"
                    class="btn btn-dark preview-toolbar-button"
                    aria-disabled="true"
                    data-bs-toggle="tooltip"
                    data-placement="bottom"
                    title="Ανέβασμα υπογεγραμμένων εγγράφων"
                >
                    <i class="fas fa-upload"></i><br />
                    <span>Ανέβασμα</span>
                </a>
                <a
                    :href="signedUrl"
                    class="btn btn-dark preview-toolbar-button"
                    aria-disabled="true"
                    data-bs-toggle="tooltip"
                    data-placement="bottom"
                    title="Λήψη υπογεγραμμένων εγγράφων"
                >
                    <i class="fas fa-signature"></i><br />
                    <span>Λήψη</span>
                </a>
            </div>
        </div>
    </div>
</template>
