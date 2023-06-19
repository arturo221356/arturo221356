<template>
  <div>
    <date-sucursal-picker-component
      titeName="Reporte de Equipos"
      postUrl="/get/imeis-vendidos"
      v-on:is-loading="isLoading = $event"
      v-on:data-loaded="dataLoaded($event)"
    ></date-sucursal-picker-component>

    <div class="col-lg-11 mx-auto mt-3">
      <div class="col-sm-6 mt-2 float-left mb-4">
        Cantidad: {{ countItems }},
        <div v-if="can('full update stock')">Ganancia: ${{ totalVentas }}</div>
      </div>
      <div class="col-sm-6 mt-2 float-right mb-4">
        <b-form-group label="Filtrar:" label-size="sm"
          ><b-input placeholder="Filtrar" type="search" v-model="filter"></b-input>
        </b-form-group>
      </div>
      <b-table
        striped
        hover
        :items="items"
        :fields="tableFields"
        :busy="isLoading"
        :filter="filter"
        responsive
        head-variant="dark"
        class="position-relative"
        @filtered="tableFiltered"
      >
        <!--busy template-->
        <template v-slot:table-busy>
          <div class="text-center text-primary my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Cargando...</strong>
          </div>
        </template>

        <template v-slot:table-caption>Resultado: - {{ countItems }} </template>
        <template v-slot:cell(ganancia)="row">
          {{ row.item.venta[0].pivot.price - row.item.venta[0].pivot.cost }}
        </template>
      </b-table>
    </div>
  </div>
</template>

<script>
import DateSucursalPickerComponent from "./dateSucursalPickerComponent.vue";
export default {
  components: {
    DateSucursalPickerComponent,
  },

  data() {
    return {
      isLoading: false,

      items: [],

      filter: null,

      countItems: 0,

      totalVentas: 0,
    };
  },
  methods: {
    dataLoaded(data) {
      this.items = data;
      this.countItems = data.length;
      if (data.length > 0) {
        let precio = data
          .map((o) => Number(o.venta[0].pivot.price))
          .reduce((a, c) => {
            return Number(a) + Number(c);
          });
        let costo = data
          .map((o) => Number(o.venta[0].pivot.cost))
          .reduce((a, c) => {
            return Number(a) + Number(c);
          });

        this.totalVentas = precio - costo;
      } else {
        this.totalVentas = 0;
      }
    },
    tableFiltered(filteredItems) {
      this.countItems = filteredItems.length;

      if (filteredItems.length > 0) {
        let precio = filteredItems
          .map((o) => Number(o.venta[0].pivot.price))
          .reduce((a, c) => {
            return Number(a) + Number(c);
          });
        let costo = filteredItems
          .map((o) => Number(o.venta[0].pivot.cost))
          .reduce((a, c) => {
            return Number(a) + Number(c);
          });

        this.totalVentas = precio - costo;
      } else {
        this.totalVentas = 0;
      }
    },
  },
  computed: {
    tableFields: function () {
      var fields = [
        {
          key: "imei",
          label: "Imei",
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "equipo.marca",
          label: "Marca",
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "equipo.modelo",
          label: "Modelo",
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "venta[0].user.name",
          label: "Vendedor",
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "venta[0].inventario.inventarioable.name",
          label: "Inventario",
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "venta[0].created_at",
          label: "Fecha",
          sortable: true,
          sortDirection: "desc",
        },
        {
          key: "venta[0].pivot.price",
          label: "Precio vendido",
          sortable: true,
          sortDirection: "desc",
        },
      ];
      if (this.can("full update stock")) {
        fields.push(
          {
            key: "venta[0].pivot.cost",
            label: "Costo",
            sortable: true,
            sortDirection: "desc",
          },
          {
            key: "ganancia",
            label: "Ganancia",
            sortable: true,
            sortDirection: "desc",
          }
        );
      }
      return fields;
    },
  },
};
</script>

<style></style>
