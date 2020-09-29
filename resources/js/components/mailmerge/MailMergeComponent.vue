<template>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <mailmerge-doc-logo></mailmerge-doc-logo>
                </div>
                <div class="row">
                    <mailmerge-doc-contact-info></mailmerge-doc-contact-info>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <mailmerge-doc-date-priority></mailmerge-doc-date-priority>
                </div>
                <div class="row">
                    <mailmerge-recipients></mailmerge-recipients>
                </div>
            </div>
            <!--<div class="form-row">
                <label for="fieldtitleid" class="col-3 col-form-label">Field title:</label>
                <div class="col-9 align-self-center">
                    <editable-text :edittext.sync="title" :fid="'field['+this.id+'][title]'"></editable-text>
                </div>
            </div>-->

        </div>
        <div class="form-group">
            <label for="docsubj">Subject:</label>
            <textarea id="docsubj" name="docsubj" class="form-control">
            </textarea>
        </div>
        <div class="form-group">
            <label for="doctext">Text:</label>
            <textarea id="doctext" name="doctext" class="form-control" rows="10">
            </textarea>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
        },
        mounted() {
            console.log('MailMerge mounted.')
        },
        data: function() {
            return {
                text: ''
            }
        },
        methods: {
        }
    }
</script>
