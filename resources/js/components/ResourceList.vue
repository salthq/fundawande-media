<template>
  <div>
    <resource-table :filtered_resources="filteredResources"></resource-table>
  </div>
</template>

<script>
export default {
  props: ["full_resources"],
  data() {
    return {};
  },
  methods: {
    bytesToHuman(num) {
      // Derived from https://gist.github.com/james2doyle/4aba55c22f084800c199
      var exponent;
      var unit;
      var neg = num < 0;
      var units = ["b", "kb", "mb", "gb", "tb", "pb"];

      if (neg) {
        num = -num;
      }

      if (num < 1) {
        return (neg ? "-" : "") + num + " B";
      }

      exponent = Math.min(
        Math.floor(Math.log(num) / Math.log(1000)),
        units.length - 1
      );
      num = (num / Math.pow(1000, exponent)).toFixed(2) * 1;
      unit = units[exponent];

      return (neg ? "-" : "") + num + " " + unit;
    },
    formatType(mime) {
      //Checks for a back slash, returns everything before it
      const regex = /^.*(?=(\/))/g;

      let formattedType = mime.match(regex);

      return formattedType.toString();
    },
    formatDate(created_at) {
      let date = new Date(created_at);
      let month = String(date.getMonth() + 1);
      let day = String(date.getDate());
      const year = String(date.getFullYear());

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;

      return `${day}/${month}/${year}`;
    }
  },
  computed: {
    filteredResources: function() {
      const filtered_resources = this.full_resources.map(resource => {
        // Weird object destructuring + spread operator hack to remove unwanted properties from the table
        const {
          id,
          created_at,
          updated_at,
          size,
          mime,
          ...properties
        } = resource;

        return {
          ...properties,
          date: this.formatDate(created_at),
          type: this.formatType(mime),
          size: this.bytesToHuman(size)
        };
      });

      return filtered_resources;
    }
  }
};
</script>
