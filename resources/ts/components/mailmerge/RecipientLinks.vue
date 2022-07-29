<template>
    <div class="form-group">
        <label v-if="linksObj.length">{{ __('Aliases') }}</label>
        <input type="text" class="form-control" hidden name="del_aliases" :value="delLinkJson">
        <h4>
            <span v-for="link in linksObj" :key="link.id" :id="linkId(link.id)" class="badge badge-primary m-1">{{
                    link.name
            }}
                <button type="button" class="btn btn-primary mx-1" @click="removeLink(linkId(link.id))">
                    <i class="fas fa-times"></i>
                </button>
            </span>
        </h4>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, Ref } from "vue";
import __ from "../../trans";

type Link = {
    id: number,
    name: string,
}

const props = defineProps<{
    links: string,
}>();

const linksObj: Link[] = JSON.parse(props.links);
const delLink: Ref<string[]> = ref([]);

const removeLink = (id: string) => {
    delLink.value.push(id);
    document.querySelectorAll('span#' + id).forEach(function (el) {
        el.classList.add('d-none')
    });
}

const linkId = (id: number) => 'l' + id;

const delLinkJson = computed(() => JSON.stringify(delLink.value));
</script>
