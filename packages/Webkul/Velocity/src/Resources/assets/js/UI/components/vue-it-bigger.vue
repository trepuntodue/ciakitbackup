<template>
  <div id="app">
    <div>
      <ul style="margin: 1rem 0 0 0; padding: 0">
        <li
          v-for="(image, index) in media"
          :key="index"
          class="tb-gallery"
        >
          <img
            :src="image.thumb"
            @click="openGallery(index)"
          />
        </li>
      </ul>
      <LightBox
        ref="lightbox"
        :media="media"
        :show-caption="true"
        :show-light-box="false"
        :nThumbs="1"
      />
    </div>
  </div>
</template>

<script>
import LightBox from "vue-it-bigger";
import("vue-it-bigger/dist/vue-it-bigger.min.css");

export default {
  components: {
    LightBox,
  },
  props: ["master_id", "tipo"],
  mounted() {
    this.loadMedia(this.master_id, this.tipo);
  },
  data() {
    return {
      media: [],
    };
  },
  methods: {
    openGallery(index) {
      this.$refs.lightbox.showImage(index);
    },
    loadMedia: function (master_id, tipo) {
      axios
        .get("/api/" + tipo + "/lightbox-imgs/" + master_id)
        .then((response) => {
          this.media = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
}; // PWS#13-lightbox
</script>
