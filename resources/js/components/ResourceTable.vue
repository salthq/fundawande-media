<template>
  <div>
    <b-container fluid class="my-3 mr-3">
      <b-row class="justify-content-end">
        <b-card no-body class="py-2 mr-3 d-flex justify-content-end">
          <b-col md="12">
            <b-form-group label-cols-sm="3" label="Filter" class="mb-0">
              <b-input-group>
                <b-form-input v-model="filter" placeholder="Type to Search"></b-form-input>
                <b-input-group-append>
                  <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-card>
      </b-row>
    </b-container>

    <b-table
      striped
      hover
      :items="filtered_resources"
      :current-page="currentPage"
      :fields="fields"
      :filter="filter"
      :per-page="perPage"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      @filtered="onFiltered"
    >
      <template slot="index" slot-scope="data">{{ data.index + 1 }}</template>

      <template slot="actions" slot-scope="data">
        <b-button size="sm" variant="danger" class="mr-1" @click="deleteResource(data.item.id)">
          <i class="far fa-trash-alt"></i>
        </b-button>
        <b-button size="sm" variant="info" class="mr-1">
          <i class="fas fa-eye text-white"></i>
        </b-button>
        <b-button size="sm" variant="primary" class="mr-1" @click="copyFileName(data.item.id)">
          <i class="far fa-copy"></i>
        </b-button>
        <input type="hidden" :id="'resource' + data.item.id" :value="data.item.filename">
      </template>
    </b-table>

    <b-row class="justify-content-end">
      <b-col md="6" class="my-1 mr-3 d-flex justify-content-end">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  props: ["filtered_resources"],
  data() {
    return {
      totalRows: 1,
      currentPage: 1,
      filter: null,
      perPage: 10,
      sortBy: "created_at",
      sortDesc: false,
      fields: [
        "index",
        { key: "title", sortable: true },
        { key: "type", sortable: true },
        { key: "date", sortable: true },
        { key: "size", sortable: false },
        { key: "actions", label: "Actions" }
      ]
    };
  },
  methods: {
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    deleteResource(id) {
      axios.post("/resources/" + id, { _method: "delete" }).then(response => {
        this.filtered_resources = this.filtered_resources.filter(
          post => post.id != id
        );
      });
    },
    copyFileName(id) {
      let fileNameToCopy = document.querySelector("#resource" + id);
      fileNameToCopy.setAttribute("type", "text");
      fileNameToCopy.select();
      try {
        var successful = document.execCommand("copy");
        alert("File was successfully copied");
      } catch (err) {
        alert("Oops, unable to copy");
      }
      /* unselect the range */
      fileNameToCopy.setAttribute("type", "hidden");
      window.getSelection().removeAllRanges();
    }
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.filtered_resources
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key };
        });
    }
  },
  mounted() {
    this.totalRows = this.filtered_resources.length;
  }
};
</script>
