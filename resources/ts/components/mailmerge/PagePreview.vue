<script setup lang="ts">
import { ref, onMounted, computed, Ref } from "vue";
import Fuse from "fuse.js";
import { Modal } from "bootstrap";
import PagePreviewToolbar from "./PagePreviewToolbar.vue";
import PagePreviewDocument from "./PagePreviewDocument.vue";
import RecipientModal from "./RecipientModal.vue";

const props = defineProps<{
    editor_address: string;
    editor_name: string;
    editor_telephone: string;
    editor_email: string;
    doc_logo_image: string;
    doc_logo_text: string;
    exact_copy_text: string;
    signature_text: string;
    protocol_num: string;
    doc_ada: string;
    doc_date: string;
    doc_subject: string;
    doc_text: string;
    doc_recipient_fields: string;
    xls_data: string;
    print_url: string;
    save_mail_merge_url: string;
    recipient_list_url: string;
    edit_mailmerge_url: string;
    store_many_url: string;
    upload_url: string;
    signed_url: string;
    app_url: string;
    files_for_teachers: boolean;
}>();

type UnknownRecipient = {
    color: string;
    name: string;
    icon: string;
    percentage: number;
};

type UnknownRecipientOptions = {
    name: string;
    value: string;
};

type UnknownRecipientSelected = {
    [key: string]: string;
};

const setIds = (data: XLSX_JSON[]) => {
    let i = 1;
    data.forEach((el) => {
        el.id = i;
        i++;
    });
    return data;
};

const records = ref<XLSX_JSON[]>(setIds(JSON.parse(props.xls_data)));
const current_record = ref(0);
const recipient_fields = JSON.parse(props.doc_recipient_fields) as string[];
const progress = ref(0);
const to_select = ref(0);
const unknown_recipients: Ref<UnknownRecipient[]> = ref([]);
const ur_options: Ref<UnknownRecipientOptions[]> = ref([]);
const ur_selected: Ref<UnknownRecipientSelected> = ref({});
const errorMessage = ref("");
const saveMailMergeReady = ref(false);
const saveMailMergeLoading = ref(false);
const saveRecipientsLoading = ref(false);

const progress_style = computed(() => `width: ${progress.value}%`);

const currentRecordData = computed(
    () => records.value[current_record.value] ?? ({} as XLSX_JSON),
);
const currentRecordDisplay = computed(() => current_record.value + 1);
const lastRecord = computed(
    () => records.value[records.value.length - 1]?.id ?? 0,
);

const zoomLevel = computed(() => {
    const lvl: string[] = [];
    for (let i = 10; i <= 100; i += 10) {
        lvl.push(i + "%");
    }
    return lvl;
});

const logo_img = computed(() => {
    return (
        props.app_url +
        (props.app_url.endsWith("/") ? "images/" : "/images/") +
        props.doc_logo_image
    );
});

const doc_logo_text_html = computed(() =>
    props.doc_logo_text.replace(/\n/g, "<br/>"),
);
const exact_copy_html = computed(() =>
    props.exact_copy_text.replace(/\n/g, "<br/>"),
);
const signature_html = computed(() =>
    props.signature_text.replace(/\n/g, "<br/>"),
);
const amLabel = computed(() => {
    const num = String(currentRecordData.value["ΑΜ"] ?? "");
    const parsed = parseInt(num, 10);
    if (!num || Number.isNaN(parsed)) {
        return "Α.Μ.";
    }
    return parsed < 1000000 ? "Α.Μ." : "Α.Φ.Μ.";
});

const replaceFields = (text: string) => {
    const pattern = /\[\[.+?\]\]/g;
    const matches: string[] = [];
    let result: RegExpExecArray | null;

    // Βρες όλες τις ετικέτες
    while ((result = pattern.exec(text)) !== null) {
        matches.push(result[0]);
    }

    matches.forEach((match) => {
        const field = match.slice(2, match.length - 2);
        if (typeof currentRecordData.value[field] !== "undefined") {
            text = text.replaceAll(
                match,
                String(currentRecordData.value[field]),
            );
        } else {
            text = text.replaceAll(match, "");
        }
    });

    return text;
};

const docTextHtml = computed(() => replaceFields(props.doc_text));

const recipientsHtml = computed(() => {
    const html = [
        "<p class='font-weight-bold mb-0'>ΠΡΟΣ</p>",
        "<p>",
        "[[ΕΠΩΝΥΜΟ]] [[ΟΝΟΜΑ]]<br/>",
        "ΚΛΑΔΟΥ: [[ΚΛΑΔΟΣ]]<br/>",
        `<span id='am'>${amLabel.value}</span>: [[ΑΜ]]<br/>`,
        props.files_for_teachers ? "" : "(δια της σχολικής μονάδας)",
        "</p>",
    ].join("");

    return replaceFields(html);
});

const recipientListHtml = computed(() => {
    let recipient_list = "<p class='font-weight-bold mb-0'>ΚΟΙΝ</p><p>";
    const recipient_list_array: string[] = [];
    recipient_fields.forEach((field) => {
        const value = currentRecordData.value[field];
        if (
            value != undefined &&
            value !== "" &&
            !recipient_list_array.includes(String(value))
        ) {
            recipient_list += `${recipient_list_array.length + 1}. ${String(value)}<br/>`;
            recipient_list_array.push(String(value));
        }
    });
    recipient_list += `${recipient_list_array.length + 1}. ΑΦ ${currentRecordData.value["ΑΦ"] ?? ""}</p>`;
    return recipient_list;
});

const setZoom = (zoomValue = "100") => {
    const zoom = parseInt(String(zoomValue), 10) || 100;
    const elements = Array.from(
        document.querySelectorAll<HTMLDivElement>("div.page"),
    );
    const scale = `scale(${zoom / 100})`;
    const originString = `0% 0%`;
    const styleProperties = [
        ["transform", scale],
        ["transform-origin", originString],
        ["-webkit-transform", scale],
        ["-webkit-transform-origin", originString],
        ["-moz-transform", scale],
        ["-moz-transform-origin", originString],
        ["-ms-transform", scale],
        ["-ms-transform-origin", originString],
        ["-o-transform", scale],
        ["-o-transform-origin", originString],
    ];

    elements.forEach((element) => {
        styleProperties.forEach(([name, value]) => {
            element.style.setProperty(name, value);
        });
    });
};

const handleSetZoom = (event: Event) => {
    const target = event.target as HTMLSelectElement;
    setZoom(target?.value ?? "100");
};

const changeRecord = (value: number) => {
    if (value < 1 || value > lastRecord.value) {
        return;
    }
    current_record.value = value - 1;
};

const leftArrowClicked = () => {
    if (current_record.value > 0) {
        current_record.value--;
    }
};

const rightArrowClicked = () => {
    if (current_record.value < lastRecord.value - 1) {
        current_record.value++;
    }
};

const updateToSelect = () => {
    to_select.value = Object.values(ur_selected.value).filter(
        (value) => value === "-1",
    ).length;
};

const saveMailMergeClicked = () => {
    progress.value = 0;
    errorMessage.value = "";
    unknown_recipients.value = [];
    ur_options.value = [];
    ur_selected.value = {};
    to_select.value = 0;
    saveMailMergeReady.value = false;

    const modalElement = document.getElementById("myModal");
    if (modalElement) {
        const myModal = new Modal(modalElement, {
            backdrop: "static",
            keyboard: false,
            focus: true,
        });
        myModal.show();
    }

    fetch(props.recipient_list_url, {
        credentials: "same-origin",
        headers: {
            Accept: "application/json",
        },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to retrieve recipient list");
            }
            return response.json();
        })
        .then((data) => {
            const recipients: App.Models.Recipient[] = data;
            const max = records.value[records.value.length - 1].id;

            recipients.forEach((recipient) => {
                if (!recipient.link) {
                    ur_options.value.push({
                        value: recipient.code,
                        name: recipient.name,
                    });
                }
            });

            ur_options.value.sort((a, b) =>
                a.name < b.name ? -1 : a.name > b.name ? 1 : 0,
            );
            ur_options.value.unshift({
                value: "-1",
                name: "Παρακαλώ επιλέξτε",
            });

            const fuse = new Fuse(recipients, {
                includeScore: true,
                threshold: 0.4,
                distance: 10,
                keys: ["name"],
            });

            delayedLoop(records.value, 20, (item: XLSX_JSON, index: number) => {
                const doc_fields = JSON.parse(props.doc_recipient_fields);
                doc_fields.push("ΑΦ");

                doc_fields.forEach((field: string) => {
                    if (
                        field !== null &&
                        item[field] != "" &&
                        !recipients
                            .map((x) => x.name)
                            .includes(String(item[field])) &&
                        !recipients
                            .map((x) => x.name)
                            .find(
                                (name) =>
                                    Intl.Collator("el-GR", {
                                        sensitivity: "base",
                                    }).compare(
                                        name
                                            .normalize("NFD")
                                            .replace(/[̀-ͯ]/g, ""),
                                        String(item[field])
                                            .normalize("NFD")
                                            .replace(/[̀-ͯ]/g, ""),
                                    ) === 0,
                            ) &&
                        !unknown_recipients.value
                            .map((x) => x.name)
                            .includes(String(item[field]))
                    ) {
                        ur_selected.value[item[field]] = "-1";

                        const results = fuse.search(String(item[field]));
                        let color = "";
                        let icon = "";
                        let percentage: number;

                        if (results.length) {
                            ur_selected.value[item[field]] =
                                results[0].item.code;
                            if (typeof results[0].score === "undefined") {
                                color = "bg-danger";
                            } else if (results[0].score < 0.2) {
                                color = "bg-success";
                            } else if (results[0].score < 0.5) {
                                color = "bg-warning";
                            } else {
                                color = "bg-danger";
                            }

                            const num1 = String(item[field])
                                .slice(0, 5)
                                .match(/\d+/g);
                            const num2 = results[0].item.name
                                .slice(0, 5)
                                .match(/\d+/g);
                            const num1String = num1 == null ? "0" : num1[0];
                            const num2String = num2 == null ? "0" : num2[0];
                            percentage =
                                Math.round(
                                    (1 - parseFloat(String(results[0].score))) *
                                        10000,
                                ) / 100;

                            if (
                                parseInt(num1String, 10) !==
                                parseInt(num2String, 10)
                            ) {
                                icon = "fas fa-exclamation-triangle";
                            }
                        } else {
                            percentage = 0;
                            color = "bg-danger";
                            to_select.value++;
                        }

                        unknown_recipients.value.push({
                            name: String(item[field]),
                            icon,
                            color,
                            percentage,
                        });
                    }
                });

                update_progress(index + 1, max);
                if (index + 1 === max) {
                    if (!unknown_recipients.value.length) {
                        saveMailMergeReady.value = true;
                    }
                    sort_table();
                }
            });
        })
        .catch(() => {
            errorMessage.value = "Error retrieving recipient list!";
        });
};

const sort_table = () => {
    unknown_recipients.value.sort((a, b) => b.percentage - a.percentage);
};

const update_progress = (value: number, max: number) => {
    progress.value =
        Math.round(((value / max) * 100 + Number.EPSILON) * 100) / 100;
};

const delayedLoop = (
    collection: XLSX_JSON[],
    delay: number,
    callback: Function,
    context: object | null = null,
) => {
    context = context || null;

    let i = 0;
    const nextIteration = function () {
        if (i === collection.length) {
            return;
        }

        callback.call(context, collection[i], i);
        i++;
        setTimeout(nextIteration, delay);
    };

    nextIteration();
};

const saveRecipientsClicked = async () => {
    if (to_select.value) {
        alert("Παρακαλώ επιλέξτε αντιστοίχιση για όλους τους παραλήπτες!");
        return;
    }

    saveRecipientsLoading.value = true;

    const data = unknown_recipients.value.map((recipient) => {
        const code = ur_selected.value[recipient.name] ?? "-1";
        const option = ur_options.value.find((item) => item.value === code);
        return {
            name: recipient.name,
            code,
            link: option?.name ?? "",
        };
    });

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") ?? "";

    try {
        const response = await fetch(props.store_many_url, {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: JSON.stringify({ many: data }),
        });

        if (!response.ok) {
            throw new Error("Failed saving data");
        }

        saveMailMergeReady.value = true;
    } catch (error) {
        alert("Failed saving data");
    } finally {
        saveRecipientsLoading.value = false;
    }
};

const onRecipientSelectorChanged = (payload: {
    name: string;
    value: string;
}) => {
    ur_selected.value[payload.name] = payload.value;
    updateToSelect();
};

const readySaveMailMergeClicked = () => {
    saveMailMergeLoading.value = true;
    saveMailMergeReady.value = false;
    window.location.assign(props.save_mail_merge_url);
};

const saveMailMergeDisabled = computed(
    () => !saveMailMergeReady.value || saveMailMergeLoading.value,
);

const saveRecipientsDisabled = computed(
    () => unknown_recipients.value.length === 0 || saveRecipientsLoading.value,
);

const print_url_draft = computed(() => {
    return props.print_url + "?draft=true";
});

onMounted(() => {
    setZoom("70%");
});
</script>

<template>
    <div class="container">
        <PagePreviewToolbar
            :zoom-levels="zoomLevel"
            :current-record="currentRecordDisplay"
            :last-record="lastRecord"
            :edit-mailmerge-url="props.edit_mailmerge_url"
            :print-url-draft="print_url_draft"
            :print-url="props.print_url"
            :upload-url="props.upload_url"
            :signed-url="props.signed_url"
            @setZoom="handleSetZoom"
            @leftArrowClicked="leftArrowClicked"
            @rightArrowClicked="rightArrowClicked"
            @changeRecord="changeRecord"
            @saveMailMergeClicked="saveMailMergeClicked"
        />

        <PagePreviewDocument
            :logo-img="logo_img"
            :doc-logo-text-html="doc_logo_text_html"
            :editor-address="props.editor_address"
            :editor-name="props.editor_name"
            :editor-telephone="props.editor_telephone"
            :editor-email="props.editor_email"
            :doc-ada="props.doc_ada"
            :doc-date="props.doc_date"
            :protocol-num="props.protocol_num"
            :doc-subject="props.doc_subject"
            :doc-text-html="docTextHtml"
            :recipients-html="recipientsHtml"
            :recipient-list-html="recipientListHtml"
            :exact-copy-html="exact_copy_html"
            :signature-html="signature_html"
        />

        <RecipientModal
            :progress-style="progress_style"
            :progress="progress"
            :error-message="errorMessage"
            :unknown-recipients="unknown_recipients"
            :ur-options="ur_options"
            :ur-selected="ur_selected"
            :save-mail-merge-ready="saveMailMergeReady"
            :save-mail-merge-loading="saveMailMergeLoading"
            :save-recipients-loading="saveRecipientsLoading"
            @saveRecipientsClicked="saveRecipientsClicked"
            @readySaveMailMergeClicked="readySaveMailMergeClicked"
            @recipientSelectorChanged="onRecipientSelectorChanged"
        />
    </div>
</template>
