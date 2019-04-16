<template>
  <div class="container">
    <div class="large-12 medium-12 small-12 filezone">
      <input type="file" id="files" ref="files" multiple v-on:change="handleResources()">
      <p>
        Drop your files here
        <br>or click to search
      </p>
    </div>
    <div v-for="(resource, key) in resources" class="resource-listing" v-bind:key="key">
      <p class="text-center my-3">{{ resource.name }}</p>
      <div class="success-container" v-if="resource.id > 0">Success</div>
      <div class="remove-container" v-else>
        <a class="remove" v-on:click="removeResource(key)">Remove</a>
      </div>
    </div>
    <a class="submit-button" v-on:click="submitResources()" v-show="resources.length > 0">Submit</a>
  </div>
</template>

<script>
export default {
  props: ["post_url"],
  data() {
    return {
      resources: []
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
      this.getImagePreviews();
    },
    submitResources() {
      for (let i = 0; i < this.resources.length; i++) {
        if (this.resources[i].id) {
          continue;
        }
        let formData = new FormData();
        formData.append("resource", this.resources[i]);

        // If hitting this endpoint fails, we can create a proper uploadResource function in the controller instead.
        axios
          .post("/resources/", formData, {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          })
          .then(
            function(data) {
              this.resources[i].id = data["data"]["id"];
              this.resources.splice(i, 1, this.resources[i]);
              console.log("success");
            }.bind(this)
          )
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
  padding: 50px 50px 50px 50px;
}
div.resource-listing img {
  max-width: 90%;
}

div.resource-listing {
  margin: auto;
  padding: 10px;
  border-bottom: 1px solid #ddd;
}

div.resource-listing img {
  height: 100px;
}
div.success-container {
  text-align: center;
  color: green;
}

div.remove-container {
  text-align: center;
}

div.remove-container a {
  color: red;
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
