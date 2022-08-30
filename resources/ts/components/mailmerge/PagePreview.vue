<template>
    <div class="container">
        <!-- Page preview -->
        <div class="btn-toolbar">
            <!-- toolbar -->
            <div class="btn-toolbar mb-3" role="toolbar" aria-label="Preview toolbar">
                <div class="btn-group btn-group-lg mr-2" role="group" aria-label="First group">
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true"><span
                            class="align-middle">{{ __('Zoom') }}:</span></button>
                    <select class="btn btn-dark    " name="pagezoom" v-on:change="setZoom">
                        <option v-for="zoom in zoomLevel" :value="zoom" :key="zoom" :selected="zoom == '70%'">
                            {{ zoom }}
                        </option>
                    </select>
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true"><span
                            class="align-middle">{{ __('Record') }}:</span></button>
                    <button class="btn btn-dark" aria-disabled="true" @click="leftArrowClicked"><i
                            class="fa fa-arrow-left"></i></button>
                    <button role="button" class="btn btn-dark" aria-disabled="true" id="current_record"
                        @click="showCurrentRecordInput"><span class="align-middle">1</span></button>
                    <input type="text" class="btn-light d-none" id="current_record_input" size="3"
                        @change="currentRecordInputChanged($event)">
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true"><span
                            class="align-middle">/</span></button>
                    <button role="button" class="btn btn-dark btn-label" aria-disabled="true" id="last-record"><span
                            class="align-middle">0</span></button>
                    <button class="btn btn-dark" aria-disabled="true" @click="rightArrowClicked"><i
                            class="fa fa-arrow-right"></i></button>
                    <a :href="edit_mailmerge_url" class="btn btn-dark preview-toolbar-button" aria-disabled="true"
                        data-bs-toggle="tooltip" data-placement="bottom" title="Επεξεργασία εγγράφου"><i
                            class="fas fa-pencil-alt"></i><br /><span>Επεξεργασία</span></a>
                    <a :href="print_url_draft" target="_blank" class="btn btn-dark preview-toolbar-button"
                        aria-disabled="true" data-bs-toggle="tooltip" data-placement="bottom"
                        title="Εκτύπωση συγχωνευμένων εγγράφων (τρίπτυχο)"><i
                            class="fab fa-firstdraft"></i><br /><span>Τρίπτυχο</span></a>
                    <a :href="print_url" target="_blank" class="btn btn-dark preview-toolbar-button"
                        aria-disabled="true" data-bs-toggle="tooltip" data-placement="bottom"
                        title="Εκτύπωση συγχωνευμένων εγγράφων"><i
                            class="fas fa-print"></i><br /><span>Εκτύπωση</span></a>
                    <button class="btn btn-dark preview-toolbar-button" aria-disabled="true"
                        @click="saveMailMergeClicked" data-bs-toggle="tooltip" data-placement="bottom"
                        title="Αποθήκευση συγχωνευμένων εγγράφων"><i
                            class="fas fa-mail-bulk"></i><br /><span>Αποθήκευση</span></button>
                </div>
            </div>
        </div>
        <div class="page" size="A4">
            <table class="table table-borderless">
                <tr>
                    <td class="w-50">
                        <p class="text-center"><img :src="logo_img" width="50"></p>
                        <p class="text-center" v-html="doc_logo_text_html"></p>
                        <table class="table table-borderless doc-address-col">
                            <tr>
                                <td class="no-wrap pr-1">Ταχ. Διεύθυνση:</td>
                                <td>{{ editor_address }}</td>
                            </tr>
                            <tr>
                                <td>Πληροφορίες:</td>
                                <td>{{ editor_name }}</td>
                            </tr>
                            <tr>
                                <td>Τηλέφωνο:</td>
                                <td>{{ editor_telephone }}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>{{ editor_email }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="w-50">
                        <table class="table table-borderless doc-recipient-col">
                            <tr>
                                <td>
                                    <p class="text-right" v-if="props.doc_ada"><b>ΑΔΑ: {{ doc_ada }}</b></p>
                                    <p class="text-right">Θεσσαλονίκη, {{ doc_date }}<br />
                                        Αρ. Πρωτ.: {{ protocol_num }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-bottom" id="recipients">
                                    <p class="font-weight-bold mb-0">ΠΡΟΣ</p>
                                    <p>[[ΕΠΩΝΥΜΟ]] [[ΟΝΟΜΑ]]<br />
                                        ΚΛΑΔΟΥ: [[ΚΛΑΔΟΣ]]<br />
                                        <span id="am">Α.Μ.</span>: [[ΑΜ]]<br />
                                        <span v-if="files_for_teachers == false">
                                            (δια της σχολικής μονάδας)
                                        </span>
                                    </p>
                                    <p class="font-weight-bold mb-0">ΚΟΙΝ</p>
                                    1. ΑΦ [[ΑΦ]]<br />
                                    <span id="recipient-list"></span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <p class="font-weight-bold">Θέμα: «{{ doc_subject }}»</p>
            <p id="doc_text"></p>

            <table class="table table-borderless signature-table">
                <tr>
                    <td class="text-center" v-html="exact_copy_html">
                    </td>
                    <td class="text-center" v-html="signature_html">
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal" id="myModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Έλεγχος αποδεκτών αλληλογραφίας</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" />
                    </div>
                    <div class="modal-body">
                        <p>Γίνεται έλεγχος των αποδεκτών της αλληλογραφίας σας. Μόλις ολοκληρωθεί ο έλεγχος θα
                            ενεργοποιηθεί το κουμπί της λήψης.</p>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :style="progress_style"
                                :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">{{ progress }}%</div>
                        </div>
                        <div id="error_msg"></div>
                        <br />
                        <div id="unknown_recipients" class="d-none">
                            <p>Οι παρακάτω παραλήπτες δεν βρέθηκαν στο σύστημα για αντιστοίχιση με κωδικό σχολικής
                                μονάδας.
                                Παρακαλούμε επιλέξτε από δίπλα αν η σχολική μονάδα εμφανίζεται με άλλο όνομα.
                            </p>
                            <table class="table-striped table-bordered">
                                <thead>
                                    <th>Όνομα</th>
                                    <th>Ποσοστό ταιριάσματος</th>
                                    <th>Αντιστοίχιση</th>
                                </thead>
                                <tbody>
                                    <tr v-for="unknown_recipient in unknown_recipients" :key="unknown_recipient.name"
                                        :class="unknown_recipient.color">
                                        <td>{{ unknown_recipient.name }}</td>
                                        <td><i v-if="unknown_recipient.icon" :class="unknown_recipient.icon"></i>{{
                                            unknown_recipient.percentage
                                            }}%
                                        </td>
                                        <td>
                                            <select name='recipient' @change='recipientSelectorChanged'
                                                v-model="ur_selected[unknown_recipient.name]">
                                                <option v-for="ur_option in ur_options" :key="ur_option.value"
                                                    :value="ur_option.value">{{ ur_option.name }}</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Άκυρο</button>
                            <button type="button" class="btn btn-primary d-none" id="save_recipients"
                                @click="saveRecipientsClicked">Αποθήκευση αντιστοίχισης</button>
                            <button type="button" class="btn btn-primary" id="save_mail_merge"
                                @click="readySaveMailMergeClicked" disabled>
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Working...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, Ref } from 'vue';
import __ from "../../trans";
import Fuse from "fuse.js";
import { Modal } from "bootstrap";

const props = defineProps<{
    editor_address: string,
    editor_name: string,
    editor_telephone: string,
    editor_email: string,
    doc_logo_image: string,
    doc_logo_text: string,
    exact_copy_text: string,
    signature_text: string,
    protocol_num: string,
    doc_ada: string,
    doc_date: string,
    doc_subject: string,
    doc_text: string,
    doc_recipient_fields: string,
    xls_data: string,
    print_url: string,
    save_mail_merge_url: string,
    recipient_list_url: string,
    edit_mailmerge_url: string,
    store_many_url: string,
    app_url: string,
    files_for_teachers: boolean
}>();

type UnknownRecipient = {
    color: string,
    name: string,
    icon: string,
    percentage: number,
}

type UnknownRecipientOptions = {
    name: string,
    value: string,
}

type UnknownRecipientSelected = {
    [key: string]: string,
}

const setIds = (data: XLSX_JSON[]) => {
    let i = 1;
    data.forEach(el => {
        el.id = i;
        i++;
    })

    return data;
};

let records = setIds(JSON.parse(props.xls_data));
let current_record = 0;
let recipient_fields = JSON.parse(props.doc_recipient_fields);
let progress = ref(0);
let to_select = 0;
let unknown_recipients: Ref<UnknownRecipient[]> = ref([]);
let ur_options: Ref<UnknownRecipientOptions[]> = ref([]);
let ur_selected: Ref<UnknownRecipientSelected> = ref({});
let rec_text = '';

onMounted(() => {
    setZoom();
    getLastRecord();
    rec_text = $('#recipients').html();
    showCurrentRecordText();
});

const setZoom = () => {
    const transformOrigin = [0, 0];
    const pagezoom_value = $('select[name="pagezoom"').val();
    let zoom = 100;
    if (typeof pagezoom_value === "string") {
        zoom = parseInt(pagezoom_value);
    }
    const element = $('div.page');
    const property = ['webkit', 'moz', 'ms', 'o'];
    const scale = 'scale(' + zoom / 100 + ')';
    const originString = (transformOrigin[0] * 100) + '% ' + (transformOrigin[1] * 100) + '%';

    for (let i = 0; i < property.length; i++) {
        element.css(property[i] + 'Transform', scale);
        element.css(property[i] + 'TransformOrigin', originString);
    }

    element.css('transform', scale);
    element.css('transformOrigin', originString);
};


const getLastRecord = () => {
    $('#last-record span').html(String(records[records.length - 1].id));
};

const replaceFields = (text: string) => {
    const pattern = /\[\[.+?\]\]/g;
    const matches: string[] = [];
    let result: RegExpExecArray | null;

    // Βρες όλες τις ετικέτες
    while ((result = pattern.exec(text)) !== null) {
        matches.push(result[0]);
    };

    // Για κάθε ετικέτα κάνε αντικατάσταση με την αντίστοιχη τιμή
    matches.forEach(function (match) {
        const field = match.slice(2, match.length - 2);
        if (typeof records[current_record][field] !== 'undefined') {
            text = text.replaceAll(match, String(records[current_record][field]));
        } else {
            text = text.replaceAll(match, '');
        }
    })
    return text;
};

const showCurrentRecordText = () => {
    let text = props.doc_text;
    text = replaceFields(text);
    $('#doc_text').html(text);

    text = rec_text;
    text = replaceFields(text);
    $('#recipients').html(text);

    if (typeof records[current_record]['ΑΜ'] !== 'undefined') {
        const num = String(records[current_record]['ΑΜ']);
        if (parseInt(num) < 1000000) {
            $('#am').html('Α.Μ.');
        } else {
            $('#am').html('Α.Φ.Μ.');
        }
    }

    let i = 2
    let recipient_list = ''
    const recipient_list_array: string[] = []
    recipient_fields.forEach(function (field: string) {
        if (records[current_record][field] != undefined &&
            records[current_record][field] != '' &&
            (!recipient_list_array.includes(String(records[current_record][field])))) {
            recipient_list += i + '. ' + records[current_record][field] + '<br/>';
            recipient_list_array.push(String(records[current_record][field]));
            i += 1;
        }
    })
    $('#recipient-list').html(recipient_list)
};

const showCurrentRecordInput = () => {
    $('#current_record').addClass('d-none');
    $('#current_record_input').removeClass('d-none');
    $('#current_record_input').focus();
};

const currentRecordInputChanged = (e: Event) => {
    if (e.target instanceof HTMLInputElement) {
        const cur = parseInt(e.target.value);
        if (cur < 0 || cur > records[records.length - 1].id) {
            $('#current_record').removeClass('d-none');
            $('#current_record_input').addClass('d-none');
        } else {
            $('#current_record').removeClass('d-none');
            $('#current_record_input').addClass('d-none');
            $('#current_record').html(String(cur));
            current_record = cur - 1;
            showCurrentRecordText();
        }
    }
};

const leftArrowClicked = () => {
    $('#current_record').removeClass('d-none');
    $('#current_record_input').addClass('d-none');
    if (current_record > 0) {
        current_record--;
        $('#current_record').html(String(current_record + 1));
        $('#current_record_input').val(current_record + 1);
        showCurrentRecordText();
    }
};

const rightArrowClicked = () => {
    $('#current_record').removeClass('d-none');
    $('#current_record_input').addClass('d-none');
    if (current_record < (records[records.length - 1].id - 1)) {
        current_record++;
        $('#current_record').html(String(current_record + 1));
        $('#current_record_input').val(current_record + 1);
        showCurrentRecordText();
    }
};

const saveMailMergeClicked = () => {
    // Αρχικοποίησε τιμές
    progress.value = 0;
    $('#unknown_recipients').addClass('d-none');
    unknown_recipients.value = [];
    ur_selected.value = {};
    $('#save_mail_merge').html('<div class="spinner-border" role="status"><span class="sr-only">Working...</span></div>');
    $('#save_mail_merge').prop('disabled', true);

    // Εμφάνισε το modal
    const myModal = new Modal('#myModal', {
        backdrop: 'static',
        keyboard: false,
        focus: true,
    });
    myModal.show();

    let recipients: App.Models.Recipient[] = [];
    let unknown = 0;

    $.get(props.recipient_list_url, function () {
    })
        .done(function (data) {
            recipients = data;
            const max = records[records.length - 1].id;

            // Φτιάξε το combobox με τους διαθέσιμους παραλήπτες
            recipients.forEach(function (recipient) {
                if (!recipient.link) {
                    ur_options.value.push({ value: recipient.code, name: recipient.name });
                }
            });
            ur_options.value.sort(function (el1, el2) {
                if (el1.name < el2.name) {
                    return -1;
                }
                else if (el1.name > el2.name) {
                    return 1;
                }
                return 0;
            });
            ur_options.value.unshift({ value: "-1", name: 'Παρακαλώ επιλέξτε' });

            // Προετοιμασία fuzzy search
            const options = {
                // isCaseSensitive: false,
                includeScore: true,
                // shouldSort: true,
                // includeMatches: false,
                // findAllMatches: false,
                // minMatchCharLength: 1,
                // location: 0,
                threshold: 0.4,
                distance: 10,
                // useExtendedSearch: false,
                // ignoreLocation: false,
                // ignoreFieldNorm: false,
                keys: [
                    'name'
                ]
            };
            const fuse = new Fuse(recipients, options);

            delayedLoop(records, 20, function (item: XLSX_JSON, index: number) {
                const doc_fields = JSON.parse(props.doc_recipient_fields)
                doc_fields.push('ΑΦ')
                doc_fields.forEach(function (field: string) {
                    // Κοιτάει για την τιμή του πεδίου στο όνομα του παραλήπτη
                    if (field !== null &&
                        (item[field] != '') &&
                        !(recipients.map((x) => x.name).includes(String(item[field]))) &&
                        !(recipients.map((x) => x.name).includes(String(item[field]).normalize("NFD").replace(/[\u0300-\u036f]/g, ''))) &&
                        !unknown_recipients.value.map((x) => x.name).includes(String(item[field]))) {

                        $('#save_recipients').removeClass('d-none');
                        unknown++;
                        ur_selected.value[item[field]] = "-1";

                        // Fuzzy search
                        const pattern = item[field];
                        const results = fuse.search(String(pattern));
                        let color = '';
                        let icon = '';
                        var percentage: number;
                        if (results.length) {
                            ur_selected.value[item[field]] = results[0].item.code;

                            // Σημείωσε με χρώμα τα σκορ στον πίνακα
                            if (typeof results[0].score === 'undefined') {
                                color = 'bg-danger';
                            } else if (results[0].score < 0.2) {
                                color = 'bg-success';
                            } else if (results[0].score < 0.5) {
                                color = 'bg-warning';
                            } else {
                                color = 'bg-danger';
                            }

                            // Έλεγχος αριθμών
                            let num1 = String(item[field]).slice(0, 5).match(/\d+/g)
                            let num2 = results[0].item.name.slice(0, 5).match(/\d+/g)
                            let num1_string: string;
                            let num2_string: string;
                            num1_string = num1 == null ? "0" : num1[0];
                            num2_string = num2 == null ? "0" : num2[0];
                            percentage = Math.round((1 - parseFloat(String(results[0].score))) * 10000) / 100;
                            if (!(parseInt(num1_string) == parseInt(num2_string))) {
                                icon = 'fas fa-exclamation-triangle';
                            }
                        } else {
                            percentage = 0;
                            color = 'bg-danger';
                            to_select++;
                        }

                        $('#unknown_recipients').removeClass('d-none');
                        unknown_recipients.value.push({ name: String(item[field]), icon: icon, color: color, percentage: percentage });
                    }
                })
                update_progress(index + 1, max);
                if ((index + 1) == max) {
                    $('#save_mail_merge').html('Λήψη');
                    if (unknown == 0) {
                        $('#save_mail_merge').prop('disabled', false);
                    }
                    sort_table();
                }
            })
        })
        .fail(function (data) {
            $('#error_msg').html('Error retrieving recipient list!');
            $('#error_msg').addClass('alert');
            $('#error_msg').addClass('alert-danger');
        })
};

const sort_table = () => {
    unknown_recipients.value.sort(function (a, b) {
        return b.percentage - a.percentage;
    })
};

const update_progress = (value: number, max: number) => {
    progress.value = Math.round(((value / max * 100) + Number.EPSILON) * 100) / 100;
};

const delayedLoop = (collection: XLSX_JSON[], delay: number, callback: Function, context: object | null = null) => {
    context = context || null;

    let i = 0;
    const nextIteration = function () {
        if (i === collection.length) {
            return;
        }

        callback.call(context, collection[i], i);
        i++;
        setTimeout(nextIteration, delay);
    }

    nextIteration();
};

const saveRecipientsClicked = () => {
    if (to_select) {
        alert('Παρακαλώ επιλέξτε αντιστοίχιση για όλους τους παραλήπτες!')
    } else {
        let element = document.getElementById('save_recipients')
        if (element instanceof HTMLElement) {
            element.classList.add('disabled');
        }

        type NewRecipient = {
            name: string,
            code: string,
            link: string,
        }

        const data: NewRecipient[] = [];
        document.querySelectorAll('#unknown_recipients table tr').forEach((row) => {
            data.push({
                name: (row.children[0] as HTMLElement).innerText,
                code: (row.children[2].children[0] as HTMLSelectElement).selectedOptions[0].value,
                link: (row.children[2].children[0] as HTMLSelectElement).selectedOptions[0].innerText
            })
        })
        $.post({
            url: props.store_many_url,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { many: data }
        })
            .done(function (data) {
                $('#save_mail_merge').prop('disabled', false)
            })
            .fail(function (data) {
                alert('Failed saving data')
                $('#save_recipients').removeClass('disabled')
            })
    }
};

const recipientSelectorChanged = () => {
    let remaining = 0;
    for (const [key, value] of Object.entries(ur_selected)) {
        if (value == -1) { remaining++ }
    }
    to_select = remaining;
};

const readySaveMailMergeClicked = () => {
    $('#save_mail_merge').html('<div class="spinner-border" role="status"><span class="sr-only">Working...</span></div>');
    $('#save_mail_merge').prop('disabled', true);
    window.location.assign(props.save_mail_merge_url);
    // $("#save_mail_merge").html("Λήψη");
};

const zoomLevel = computed(() => {
    const lvl = [];
    for (let i = 10; i <= 100; i += 10) {
        lvl.push(i + '%');
    }
    return lvl;
});

const logo_img = computed(() => {
    return props.app_url +
        (props.app_url.endsWith('/') ? 'images/' : '/images/') +
        props.doc_logo_image;
});

const doc_logo_text_html = computed(() => {
    return props.doc_logo_text.replace(/\n/g, '<br/>');
});

const exact_copy_html = computed(() => {
    return props.exact_copy_text.replace(/\n/g, '<br/>');
});

const signature_html = computed(() => {
    return props.signature_text.replace(/\n/g, '<br/>');
});

const progress_style = computed(() => {
    return 'width: ' + progress.value + '%;';
});

const print_url_draft = computed(() => {
    return props.print_url + '?draft=true';
});

</script>
