<template>
  <div>
    <table class="table">
      <thead class="thead-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Type</th>
          <th scope="col">Resource Name</th>
          <th scope="col">Size</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(resource, index, key) in resources" v-bind:key="key">
          <td scope="row">{{ index + 1 }}</td>
          <td>{{ resource.title }}</td>
          <td>{{ resource.mime }}</td>
          <td>{{ resource.filename }}</td>
          <td>{{ resource.size }} Bytes</td>
          <td>
            <div class="btn-group" role="group" aria-label="Resource Actions">
              <form method="POST" :action="'/resources/' + resource.id">
                <slot></slot>
                <button class="btn btn-danger" type="submit">Delete</button>
              </form>
              <form method="GET" :action="'/resources/' + resource.id">
                <slot></slot>
                <button class="btn btn-info text-white" type="submit">Download</button>
              </form>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: ["resources"]
};
</script>

<style scoped>
</style>
