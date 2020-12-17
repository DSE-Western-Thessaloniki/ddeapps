<template>
    <div class="container"> <!-- Page preview -->
        <div class="btn-toolbar"> <!-- toolbar -->
            <div class="btn-toolbar" role="toolbar" aria-label="Preview toolbar">
                <div class="btn-group mr-2" role="group" aria-label="First group">
                    <a href="#" role="button" class="btn btn-light btn-label" aria-disabled="true">Zoom:</a>
                    <select class="btn btn-light    "
                            name="pagezoom"
                            v-on:change="setZoom"
                            >
                        <option v-for="zoom in zoomLevel"
                            :value="zoom"
                            :key="zoom"
                            :selected="zoom == 50">
                            {{zoom}}
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="page" size="A4">
            <p>This is a test page</p>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
        },
        mounted() {
            console.log('Pagepreview mounted.');
            this.setZoom();
        },
        data: function() {
            return {
            }
        },
        watch: {
        },
        methods: {
            setZoom: function() {
                var transformOrigin = [0,0];
                var zoom = $('select[name="pagezoom"').val();
                var el = $('div.page');
                var p = ["webkit", "moz", "ms", "o"],
                    s = "scale(" + zoom/100 + ")",
                    oString = (transformOrigin[0] * 100) + "% " + (transformOrigin[1] * 100) + "%";

                for (var i = 0; i < p.length; i++) {
                    el.css(p[i] + "Transform", s);
                    el.css(p[i] + "TransformOrigin", oString);
                }

                el.css("transform", s);
                el.css("transformOrigin", oString);
            },

            showVal: function(a){
                var zoomScale = Number(a)/10;
                setZoom(zoomScale,document.getElementsByClassName('container')[0])
            },
        },
        computed: {
            zoomLevel: function() {
                var lvl = [];
                for (var i=10; i<=100; i+=10) {
                    lvl.push(i);
                }
                return lvl;
            }
        },
    }
</script>
