<template>
    <div class="container">
        <!-- Check if something is missing -->
        <div v-if="doc_logos.length == 0">
            <div class="alert alert-danger">
                <ul>
                    <li>
                        {{ __("Cannot continue without creating a logo.") }}
                    </li>
                </ul>
            </div>
            <br />
            <a class="btn btn-primary" :href="route_doc_logo_create">
                <i class="fa-fw fas fa-plus-circle"> </i>
                {{ __("Create Logo") }}
            </a>
        </div>
        <div v-else-if="editors.length == 0">
            <div class="alert alert-danger">
                <ul>
                    <li>
                        {{ __("Cannot continue without creating an editor.") }}
                    </li>
                </ul>
            </div>
            <br />
            <a class="btn btn-primary" :href="route_editor_create">
                <i class="fa-fw fas fa-plus-circle"> </i>
                {{ __("Create Editor") }}
            </a>
        </div>
        <div v-else-if="signatures.length == 0">
            <div class="alert alert-danger">
                <ul>
                    <li>
                        {{
                            __("Cannot continue without creating a signature.")
                        }}
                    </li>
                </ul>
            </div>
            <br />
            <a class="btn btn-primary" :href="route_signature_create">
                <i class="fa-fw fas fa-plus-circle"> </i>
                {{ __("Create Signature") }}
            </a>
        </div>
        <div v-else-if="exact_copies.length == 0">
            <div class="alert alert-danger">
                <ul>
                    <li>
                        {{
                            __(
                                "Cannot continue without creating an exact copy."
                            )
                        }}
                    </li>
                </ul>
            </div>
            <br />
            <a class="btn btn-primary" :href="route_exact_copy_create">
                <i class="fa-fw fas fa-plus-circle"> </i>
                {{ __("Create Exact Copy") }}
            </a>
        </div>
        <div v-else>
            <!-- All OK, present the form -->
            <div class="row mb-3">
                <div class="col-2 mr-auto">
                    <a class="btn btn-danger" id="Cancel" :href="route_index">{{
                        __("Cancel")
                    }}</a>
                </div>
                <div
                    v-show="step == steps || func == 'edit'"
                    class="col-10 d-flex justify-content-end"
                >
                    <button class="btn btn-primary" id="Save" type="submit">
                        {{ __("Save") }}
                    </button>
                </div>
            </div>

            <div class="justify-content-center h1 row mb-3">
                <a
                    class="fas fa-fw fa-arrow-left col-auto text-decoration-none"
                    v-show="step > 1"
                    v-on:click="changeToStep(step - 1)"
                    href="#"
                ></a>
                <i
                    class="fas fa-fw fa-arrow-left col-auto"
                    v-show="step === 1"
                ></i>
                <span v-for="i in steps" :key="i" class="col-auto">
                    <i
                        v-show="step != i"
                        class="fas fa-fw fa-square show-pointer"
                        @click="changeToStep(i)"
                    ></i>
                    <i
                        v-show="step == i"
                        class="far fa-fw fa-square"
                        @click="changeToStep(i)"
                    ></i>
                </span>
                <a
                    class="fas fa-fw fa-arrow-right col-auto text-decoration-none"
                    v-show="step < steps"
                    v-on:click="changeToStep(step + 1)"
                    href="#"
                ></a>
                <i
                    class="fas fa-fw fa-arrow-right col-auto"
                    v-show="step === steps"
                ></i>
            </div>

            <div v-show="step == 1">
                <div class="card bg-success mb-3">
                    <div class="card-body">
                        <h5 class="class-title">
                            {{ __("Select data source") }}
                        </h5>
                        <div class="class-text">
                            {{
                                __(
                                    "You can select one or more columns to be used" +
                                        " as a recipient list by right clicking on each column."
                                )
                            }}
                        </div>
                    </div>
                </div>
                <div
                    id="missing-fields"
                    class="card bg-danger mb-3"
                    v-if="missingfields"
                >
                    <div class="card-body">
                        <h5 class="class-title">Σφάλμα!</h5>
                        <div class="class-text">
                            Το αρχείο πρέπει υποχρεωτικά να περιέχει τις στήλες
                            <b>ΑΜ, ΟΝΟΜΑ, ΕΠΩΝΥΜΟ, ΚΛΑΔΟΣ, ΑΦ</b>!
                        </div>
                    </div>
                </div>

                <xlsxcomponent
                    ref="xlsx_ref"
                    v-on:setmergefields="setmergefields"
                    v-on:missingfields="setMissingfields"
                    :docdata="doc_data"
                    :docdataheader="doc_data_header"
                    :mfields="doc_mfields"
                >
                </xlsxcomponent>
            </div>
            <div v-show="step == 2">
                <div class="mb-3">
                    <label for="logo_id" class="form-label">{{
                        __("Logo") + ":"
                    }}</label>
                    <select
                        class="form-select"
                        id="logo_id"
                        name="logo_id"
                        v-model="logo_selected"
                    >
                        <option
                            v-for="doc_logo in doc_logos"
                            :key="doc_logo.id"
                            :value="doc_logo.id"
                        >
                            {{ doc_logo.title }}
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="editor_id" class="form-label">{{
                        __("Editor") + ":"
                    }}</label>
                    <select
                        class="form-select"
                        id="editor_id"
                        name="editor_id"
                        v-model="editor_selected"
                    >
                        <option
                            v-for="editor in editors"
                            :key="editor.id"
                            :value="editor.id"
                        >
                            {{ editor.title }}
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ada" class="form-label">ΑΔΑ:</label>
                    <input
                        type="text"
                        id="ada"
                        name="ada"
                        class="form-control"
                        v-model="ada"
                    />
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="protocol_num">{{
                                __("Protocol number") + ":"
                            }}</label>
                            <input
                                type="text"
                                id="protocol_num"
                                name="protocol_num"
                                class="form-control"
                                required
                                v-model="prot_num"
                            />
                        </div>
                    </div>

                    <div class="col">
                        <div class="mb-3">
                            <label for="date">{{ __("Date") + ":" }}</label>
                            <input
                                type="date"
                                id="date"
                                name="date"
                                :value="get_date"
                                class="form-control"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="step == 3">
                <div class="mb-3">
                    <label for="subject">{{ __("Subject") + ":" }}</label>
                    <textarea
                        id="subject"
                        name="subject"
                        class="form-control"
                        v-model="subject"
                    >
                    </textarea>
                </div>
                <div class="mb-3">
                    <label for="text">{{ __("Text") + ":" }}</label>
                    <textarea
                        id="text"
                        name="text"
                        class="form-control"
                        rows="10"
                        v-model="editorData"
                        hidden
                    >
                    </textarea>
                    <ckeditor
                        ref="editorRef"
                        v-model="editorData"
                        :config="editorConfig"
                        @ready="ckEditorReadyCallback"
                        @update:modelValue="ckEditorReadyCallback"
                    />
                </div>
            </div>

            <div v-show="step == 4">
                <div class="mb-3">
                    <label for="exact_copy_id" class="form-label">{{
                        __("Exact Copy") + ":"
                    }}</label>
                    <select
                        class="form-select"
                        id="exact_copy_id"
                        name="exact_copy_id"
                        v-model="exact_copy_selected"
                    >
                        <option
                            v-for="exact_copy in exact_copies"
                            :key="exact_copy.id"
                            :value="exact_copy.id"
                        >
                            {{ exact_copy.title }}
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="signature_id" class="form-label">{{
                        __("Signature") + ":"
                    }}</label>
                    <select
                        class="form-select"
                        id="signature_id"
                        name="signature_id"
                        v-model="signature_selected"
                    >
                        <option
                            v-for="signature in signatures"
                            :key="signature.id"
                            :value="signature.id"
                        >
                            {{ signature.title }}
                        </option>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="files_for_teachers"
                        name="files_for_teachers"
                        v-model="fft"
                        value="1"
                    />
                    <label class="form-check-label" for="files_for_teachers"
                        >Ετοίμασε αρχείο και για τον εκπαιδευτικό</label
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import XlsxComponent from "./XlsxComponent.vue";
import { ref, computed, onMounted, getCurrentInstance } from "vue";
import "../../../../public/resources/js/ckeditor/ckeditor.js";
import Editor from "@mayasabha/ckeditor4-vue3";
import __ from "../../trans";

getCurrentInstance()?.appContext.app.use(Editor);

onMounted(() => {
    console.log("MailMerge mounted");
});

const props = withDefaults(
    defineProps<{
        doc_logos_str: string;
        doc_logos_selected?: string | null;
        editors_str: string;
        editors_selected?: string | null;
        signatures_str: string;
        signatures_selected?: string | null;
        exact_copies_str: string;
        exact_copies_selected?: string | null;
        protocol_num?: string | null;
        doc_date?: string | null;
        doc_subject?: string | null;
        doc_text?: string | null;
        doc_data?: string | null;
        doc_data_header?: string | null;
        doc_mfields?: string | null;
        doc_ada?: string | null;
        route_exact_copy_create: string;
        route_doc_logo_create: string;
        route_signature_create: string;
        route_editor_create: string;
        route_index: string;
        func: string;
        files_for_teachers?: boolean;
    }>(),
    {
        doc_data: "",
        doc_data_header: "",
        doc_mfields: "",
    }
);

const xlsx_ref = ref();
const step = ref(1);
const steps = 4;
const editorRef = ref<typeof Editor | null>(null);
const editorData = ref(props.doc_text ?? "");
const editorConfig = {
    language: "el",
    removePlugins: ["stylescombo", "forms", "exportpdf", "div", "bidi"],
    extraPlugins: [
        "autocomplete",
        "placeholder",
        "placeholder_select",
        "textmatch",
        "font",
    ],
    placeholder_select: {
        placeholders: ["Firstname", "Lastname", "Email"],
    },
    scayt_autoStartup: true,
    entities: false,
    entities_greek: false,
    versionCheck: false,
    forcePasteAsPlainText: true,
};
const placeholders: { id: number; title: string }[] = [];
const autocomplete = ref({});
const prot_num = ref(props.protocol_num);
const subject = ref(props.doc_subject ?? "");
const config: {
    textTestCallback?: Function;
    dataCallback?: Function;
    itemTemplate?: string;
    outputTemplate?: string;
} = {};
const logo_selected = ref(props.doc_logos_selected);
const editor_selected = ref(props.editors_selected);
const signature_selected = ref(props.signatures_selected);
const exact_copy_selected = ref(props.exact_copies_selected);
const ada = ref(props.doc_ada);
const fft = ref(props.files_for_teachers);
let runCount = 0; // Used to run the ckeditor callback once

const setmergefields = (fields: string[]) => {
    const new_placeholders: { id: number; title: string }[] = new Array();
    let i = 1;
    fields.forEach(function (field) {
        new_placeholders.push({ id: i, title: field });
        i++;
    });
    window.itemsArray = new_placeholders;
    editorConfig.placeholder_select.placeholders = fields;
    // @ts-ignore
    CKEDITOR.instances.editor1.config.placeholder_select.placeholders =
        JSON.parse(JSON.stringify(fields));
    // @ts-ignore
    CKEDITOR.instances.editor1.ui.instances.placeholder_select.buildList();
};

const missingfields = ref(false);

const setMissingfields = (value: boolean) => (missingfields.value = value);

const changeToStep = (value: number) => {
    if (missingfields.value) return;
    step.value = value;
};

const myAutocomplete = (editor: any, config: any) => {
    autocomplete.value = new CKEDITOR.plugins.autocomplete(
        window.CKEDITOR.instances.editor1,
        config
    );
    // Override default getHtmlToInsert to enable rich content output.
    autocomplete.value.getHtmlToInsert = function (item) {
        return this.outputTemplate.output(item);
    };
};

const ckEditorReadyCallback = (readyEvent: Event) => {
    if (runCount == 0) {
        runCount++;

        window.itemsArray = placeholders;

        function matchCallback(text: string, offset: number) {
            const pattern = /\[{2}([A-zΑ-ω]|\])*$/;
            const match = text.slice(0, offset).match(pattern);

            if (!match) {
                return null;
            }

            return {
                start: match.index,
                end: offset,
            };
        }

        function textTestCallback(range: { collapsed: boolean }) {
            if (!range.collapsed) {
                return null;
            }

            // @ts-ignore
            return CKEDITOR.plugins.textMatch.match(range, matchCallback);
        }

        config.textTestCallback = textTestCallback;

        function dataCallback(
            matchInfo: { query: string },
            callback: Function
        ) {
            const data = window.itemsArray.filter(function (item) {
                const itemName = "[[" + item.title + "]]";
                return (
                    itemName
                        .toUpperCase()
                        .indexOf(matchInfo.query.toUpperCase()) == 0
                );
            });

            callback(data);
        }

        config.dataCallback = dataCallback;

        config.itemTemplate =
            '<li data-id="{id}">' +
            '<div><strong class="item-title">{title}</strong></div>' +
            "</li>";
        config.outputTemplate = "[[{title}]]<span>&nbsp;</span>";

        myAutocomplete(readyEvent, config);
        xlsx_ref.value.parseDocData();
    }
};

const doc_logos = computed(() => {
    return JSON.parse(props.doc_logos_str);
});

const editors = computed(() => {
    return JSON.parse(props.editors_str);
});

const signatures = computed(() => {
    return JSON.parse(props.signatures_str);
});

const exact_copies = computed(() => {
    return JSON.parse(props.exact_copies_str);
});

const get_date = computed(() => {
    if (typeof props.doc_date === "undefined" || props.doc_date == "") {
        return new Date().toISOString().slice(0, 10);
    }
    return props.doc_date;
});
</script>
