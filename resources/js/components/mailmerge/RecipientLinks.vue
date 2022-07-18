<template>
    <div class="form-group">
        <label v-if="linksObj.length">{{ __('Aliases') }}</label>
        <input type="text" class="form-control" hidden name="del_aliases" :value="delLinkJson">
        <h4>
            <span v-for="link in linksObj" :key="link.id" :id="linkId(link.id)" class="badge badge-primary m-1">{{ link.name }}
                <button type="button" class="btn btn-primary mx-1" @click="removeLink(linkId(link.id))">
                    <i class="fas fa-times"></i>
                </button>
            </span>
        </h4>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    links: String,
});

const linksObj = JSON.parse(props.links);
const delLink = ref([]);

const removeLink = (id) => {
    delLink.value.push(id);
    document.querySelectorAll('span#' + id).forEach(function (el) {
        el.classList.add('d-none')
    });
}

const linkId = (id) => 'l' + id;

const delLinkJson = computed(() => JSON.stringify(delLink.value));
</script>
