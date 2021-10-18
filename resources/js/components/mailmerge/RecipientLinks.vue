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

<script>

export default {
  props: {
    links: String
  },
  mounted () {
  },
  data: function () {
    return {
      linksObj: JSON.parse(this.links),
      delLink: []
    }
  },
  methods: {
    removeLink: function (id) {
      this.delLink.push(id)
      document.querySelectorAll('span#' + id).forEach(function (el) {
        el.classList.add('d-none')
      })
    },
    linkId: function (id) {
      return 'l' + id
    }
  },
  computed: {
    delLinkJson: function () {
      return JSON.stringify(this.delLink)
    }
  }
}
</script>
