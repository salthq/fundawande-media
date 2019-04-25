<template>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead class="thead-light">
        <tr>
          <th class="px-2" v-for="(col, key) in columns" :key="key" @click="sortTable(col)">
            {{ col }}
            <span v-if="sortColumn == col && ascending == true" class="px-2">
              <i class="fas fa-sort-up"></i>
            </span>
            <span v-else-if="sortColumn == col && ascending == false" class="px-2">
              <i class="fas fa-sort-down"></i>
            </span>
          </th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(resource, index, key) in resources" v-bind:key="key">
          <td v-for="(col, key) in columns" :key="key">{{ resource[col] }}</td>
          <td>
            <div class="btn-group" role="group" aria-label="Resource Actions">
              <form method="POST" :action="'/resources/' + resource.id">
                <slot></slot>
                <button class="btn btn-danger" type="submit">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
              <form method="GET" :action="'/resources/' + resource.id">
                <slot></slot>
                <button class="btn btn-info text-white" type="submit">
                  <i class="fas fa-download"></i>
                </button>
              </form>
              <button class="btn btn-primary text-white" type="submit">
                <i class="fas fa-copy"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: ["resource_array"],
  data() {
    return {
      ascending: false,
      resources: this.resource_array,
      sortColumn: ""
    };
  },
  methods: {
    sortTable: function sortTable(col) {
      if (this.sortColumn === col) {
        this.ascending = !this.ascending;
      } else {
        this.ascending = true;
        this.sortColumn = col;
      }

      var ascending = this.ascending;

      this.resources.sort(function(a, b) {
        if (a[col] > b[col]) {
          return ascending ? 1 : -1;
        } else if (a[col] < b[col]) {
          return ascending ? -1 : 1;
        }
        return 0;
      });
    }
  },
  computed: {
    columns: function columns() {
      if (this.resource_array.length == 56) {
        return [];
      } else {
        return Object.keys(this.resource_array[0]);
      }
    }
  }
};
</script>

<style scoped>
th:hover {
  background-color: #686868;
  color: #fff;
  cursor: pointer;
}
</style>
