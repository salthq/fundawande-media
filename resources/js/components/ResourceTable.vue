<template>
  <div>
    <b-container fluid class="my-3 mr-3">
      <b-row>
        <b-col md="6" class="my-1">
          <b-form-group label-cols-sm="3" label="Filter" class="mb-0">
            <b-input-group>
              <b-form-input v-model="filter" placeholder="Type to Search"></b-form-input>
              <b-input-group-append>
                <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col md="6" class="my-1">
          <b-form-group label-cols-sm="3" label="Sort" class="mb-0">
            <b-input-group>
              <b-form-select v-model="sortBy" :options="sortOptions">
                <option slot="first" :value="null">-- none --</option>
              </b-form-select>
              <b-form-select v-model="sortDesc" :disabled="!sortBy" slot="append">
                <option :value="false">Asc</option>
                <option :value="true">Desc</option>
              </b-form-select>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col md="6" class="my-1">
          <b-form-group label-cols-sm="3" label="Sort direction" class="mb-0">
            <b-input-group>
              <b-form-select v-model="sortDirection" slot="append">
                <option value="asc">Asc</option>
                <option value="desc">Desc</option>
                <option value="last">Last</option>
              </b-form-select>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col md="6" class="my-1">
          <b-form-group label-cols-sm="3" label="Per page" class="mb-0">
            <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
    </b-container>

    <div class="table-responsive">
      <b-table
        striped
        hover
        :items="getItems"
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
          <b-button
            id="delete"
            size="sm"
            variant="danger"
            class="action_button mr-1 my-1"
            @click="showDeletionModal(data.item.id, $event.target)"
          >
            <i class="far fa-trash-alt"></i>
          </b-button>
          <b-link
            id="view"
            class="action_button btn btn-sm btn-info mr-1 my-1"
            target="_blank"
            :href="'/storage/resources/' + data.item.filename"
          >
            <i class="fas fa-eye text-white"></i>
          </b-link>
          <b-button
            id="copy"
            size="sm"
            variant="primary"
            class="action_button mr-1 my-1"
            @click="copyFileName(data.item.id)"
          >
            <i class="far fa-copy"></i>
          </b-button>
          <b-button
            id="edit"
            size="sm"
            variant="secondary"
            class="action_button mr-1 my-1"
            @click="showEditModal(data.item.id)"
          >
            <i class="fas fa-wrench text-white"></i>
          </b-button>
          <input type="hidden" :id="'resource' + data.item.id" :value="data.item.filename">
          <b-modal
            ref="my-modal"
            hide-footer
            :id="'modal-' + data.item.id"
            title="Using Component Methods"
          >
            <div class="d-block">
              <h3>Edit Resource Title</h3>
              <form method="POST" id="form" @submit.prevent="editResourceTitle">
                <input
                  type="text"
                  name="title"
                  class="form-control my-3"
                  :placeholder="data.item.title"
                  v-model="editedTitle"
                  required
                >
                <button @click="editResourceTitle(data.item.id)" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </b-modal>
        </template>
      </b-table>
    </div>

    <b-row class="justify-content-center justify-content-md-end">
      <b-col
        md="12"
        class="my-2 d-flex justify-content-center justify-content-md-end pagination_options"
      >
        <b-form-group label-cols-sm="6" label="Per page" class="mb-0 mx-3">
          <b-form-select v-model="perPage" :options="pageOptions"></b-form-select>
        </b-form-group>
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>

    <!-- Deletion Confirmation Modal -->
    <b-modal
      :id="deletionModal.id"
      :title="deletionModal.title"
      @ok="deleteResource(deletionModal.item)"
      @hide="resetDeletionModal()"
    >{{ deletionModal.content }}</b-modal>

    <!-- Delete Button Tooltip -->
    <b-tooltip target="delete" title="Delete resource"></b-tooltip>

    <!-- View Button Tooltip -->
    <b-tooltip target="view" title="View resource"></b-tooltip>

    <!-- Copy Button Tooltip -->
    <b-tooltip target="copy" title="Copy resource"></b-tooltip>

    <!-- Edit Button Tooltip -->
    <b-tooltip target="edit" title="Edit resource"></b-tooltip>
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
      pageOptions: [5, 10, 15, 20],
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      items: [],
      fields: [
        "index",
        { key: "title", label: "Title", sortable: true },
        { key: "course_prefix", label: "Course Prefix", sortable: true },
        { key: "type", label: "Type", sortable: true },
        { key: "date", label: "Date", sortable: true },
        { key: "size", label: "Size", sortable: false },
        { key: "actions", label: "Actions" }
      ],
      deletionModal: {
        id: "deletion-modal",
        title: "Delete Resource Confirmation",
        content: "Are you sure you want to delete this resource?",
        item: ""
      },
      editedTitle: "",
      _method: "PUT"
    };
  },
  methods: {
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    showDeletionModal(item, button) {
      this.deletionModal.item = item;
      this.$root.$emit("bv::show::modal", this.deletionModal.id, button);
    },
    resetDeletionModal() {
      this.deletionModal.item = "";
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
    },
    editResourceTitle(id) {
      axios
        .post("/resources/" + id, { _method: "PUT", title: this.editedTitle })
        .then(data => {
          this.hideEditModal(id);

          let index = this.filtered_resources.findIndex(resource => {
            return resource.id === id;
          });

          if (~index) {
            this.filtered_resources[index].title = this.editedTitle;
          }
        })
        .catch(function(data) {});
    },
    showEditModal(id) {
      this.$root.$emit("bv::show::modal", `modal-${id}`);
    },
    hideEditModal(id) {
      this.$root.$emit("bv::hide::modal", `modal-${id}`);
    }
  },
  computed: {
    getItems() {
      this.items = this.filtered_resources;

      return this.items;
    },
    sortOptions() {
      // Create an options list from our fields
      return this.fields
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


<style lang="scss" scoped>
@media only screen and (min-width: 768px) {
  .pagination_options {
    fieldset {
      width: 175px;
    }
  }
}

@media only screen and (max-width: 767px) {
  .action_button {
    width: 28px;
  }
}
</style>

