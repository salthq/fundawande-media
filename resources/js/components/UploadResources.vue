<template>
  <div class="container">
    <div class="large-12 medium-12 small-12 filezone">
      <input type="file" id="files" ref="files" multiple v-on:change="handleResources()">
      <p>
        Drop your files here
        <br>or click to search
      </p>
    </div>

    <form id="form" @submit.prevent="submit" v-show="resources.length > 0">
      <!-- Slot for CSRF token -->
      <slot></slot>
      <div
        v-for="(resource, index) in resources"
        class="form-group resource-listing py-3"
        v-bind:key="index"
      >
        <h3 class="pb-2">{{ index + 1 }}</h3>
        <label for="title">Edit resource name</label>
        <input
          type="text"
          name="title[]"
          class="form-control mb-3"
          placeholder="Enter title for resource here"
          required
          v-model="resource.name"
        >
        <p>
          <b>File name:</b>
          {{ resource.name }}
        </p>

        <a class="remove-resource text-danger pb-2" v-on:click="removeResource(key)">
          <i class="far fa-trash-alt"></i> Remove Resource
        </a>
      </div>
      <b-progress
        class="w-100 my-2 px-1"
        :max="max"
        :value="uploadPercentage"
        show-progress
        animated
      ></b-progress>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="my-3 alert alert-success" role="alert" v-show="uploadSuccess">
      <p class="m-0">
        Success!
        <a class="alert-link" href="/resources">View resources?</a>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      resources: [],
      uploadSuccess: false,
      uploadPercentage: 0,
      max: 100
    };
  },
  methods: {
    handleResources() {
      let uploadedResources = this.$refs.files.files;

      for (var i = 0; i < uploadedResources.length; i++) {
        this.resources.push(uploadedResources[i]);
      }
    },
    removeResource(key) {
      this.resources.splice(key, 1);
    },
    submit() {
      for (let i = 0; i < this.resources.length; i++) {
        if (this.resources[i].id) {
          continue;
        }
        let form = document.getElementById("form");
        let formData = new FormData(form);
        formData.append("file", this.resources[i]);
        formData.append("index", i);

        axios
          .post("/resources", formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            },
            onUploadProgress: function(progressEvent) {
              this.uploadPercentage = parseInt(
                Math.round((progressEvent.loaded * 100) / progressEvent.total)
              );
            }.bind(this)
          })
          .then(data => {
            this.uploadSuccess = true;
            this.uploadPercentage = 0;
            this.resources = [];
          })
          .catch(function(data) {
            console.log(data.response);
          });
      }
    }
  }
};
</script>

<style scoped>
input[type="file"] {
  opacity: 0;
  width: 100%;
  height: 200px;
  position: absolute;
  cursor: pointer;
}
.filezone {
  outline: 2px dashed grey;
  outline-offset: -10px;
  background: #ccc;
  color: dimgray;
  padding: 10px 10px;
  min-height: 200px;
  position: relative;
  cursor: pointer;
}
.filezone:hover {
  background: #c0c0c0;
}

.filezone p {
  font-size: 1.2em;
  text-align: center;
  padding: 50px;
}

div.resource-listing {
  border-bottom: 1px solid #ddd;
}

.remove-resource {
  cursor: pointer;
}

a.submit-button {
  cursor: pointer;
  display: block;
  margin: auto;
  text-align: center;
  width: 200px;
  padding: 10px;
  text-transform: uppercase;
  background-color: #ccc;
  color: #fff !important;
  font-weight: bold;
  margin-top: 20px;
}
</style>
