<template>
  <div>
    <pre-loader v-if="!hidePreLoader"></pre-loader>
    <div v-show="hidePreLoader">
      <div class="form-row">
        <div
          v-for="(filter, index) in options.filters"
          :class="{'col-md-6': (filtersData.length === 1 && options.search), 'col-12 col-md-4 col-lg-4': (filtersData.length % 3 === 0 && !options.search || filtersData.length  === 5 )||(filtersData.length % 2 ===0 && options.search), 'col-12 col-md-6 col-lg-3': (filtersData.length % 2 ===0 && !options.search)||(filtersData.length % 3 === 0 && options.search), 'col-12 col-md-6 col-lg-6': (filtersData.length ===2 && !options.search)}"
        >
          <!-- Date Filter -->
          <label>{{$t(filter.title)}}</label>
          <div class="dateRangeFilter" v-if="filter.type === 'date_range'" :key="filter.key">
            <button class="closeDatePicker m-2" v-show="dateRangeCloseBtn" v-on:click="dateClose">X</button>
            <date-filter
              :id="filter.key"
              :label="filter.title"
              :format="dateFormat"
              :dateClear="dateClear"
              @setDatefilterValue="getDatefilterValue"
              @resetdateClears="resetDateClears"
            ></date-filter>
          </div>
          <!-- Other Dropdown Filter -->
          <div class="input-field" v-show="filter.type ==='dropdown'">
            <select :id="'filter_'+index" class="custom-select">
              <option v-for="option in filter.options" :value="option.value">
                <span v-if="filter.languageType">{{option.text}}</span>
                <span v-else>{{ $t(option.text) }}</span>
              </option>
            </select>
          </div>
        </div>
        <div
          class="form-group"
          :class="{'col-md-6 col-lg-6': (filtersData.length === 1 && options.search), 'col-12 col-md-4 col-lg-4': filtersData.length>0 && filtersData.length % 2 ===0 || filtersData.length>4, 'col-12 col-md-6 col-lg-3': filtersData.length>0 && filtersData.length % 3 ===0, 'col-12 col-md-4 col-lg-4 offset-md-8 offset-lg-8': filtersData.length==0}"
          v-if="options.search"
        >
          <label for="search">{{$t('lang.search')}}</label>
          <input id="search" type="text" class="form-control" />
        </div>
      </div>
      <pre-loader v-if="searchLoader"></pre-loader>
      <div v-else>
        <div class="row px-3">
          
          <div class="table-responsive">
            <table class="table custom-table-responsive">
              <thead>
                <template v-for="column in options.columns">
                  <th
                    :class="{'text-right': column.type=='component' , 'text-right':includes(options.right_align, column.key) }"
                  >
                    <!-- Check if Column is Sortable -->
                    <a
                      v-if="column.sortable"
                      href="#"
                      @click.prevent="changeSortingKey(column.key), selectedColumn = column.key"
                      class="sortable-table-head app-color-text"
                    >
                      <div class="data-table-sort-parent">
                        <div class="left-align data-table-sort-child">{{ $t(column.title) }}</div>
                        <div class="text-right data-table-sort-child">
                          <div v-if="columnSortedBy=='ASC' && selectedColumn==column.key">
                            <i class="fa fa-caret-down"></i>
                          </div>
                          <div v-if="columnSortedBy=='DESC' && selectedColumn==column.key">
                            <i class="fa fa-caret-up"></i>
                          </div>
                          <div v-if="selectedColumn!=column.key" class="data-table-sort-icon">
                            <i class="fa fa-sort"></i>
                          </div>
                        </div>
                      </div>
                    </a>
                    <span v-else>{{ $t(column.title) }}</span>
                  </th>

                  <th
                    v-if="column.key == options.positionAfter"
                    v-for=" name in columnName"
                  >{{name}}</th>
                </template>
              </thead>
              <tbody v-if="datasets.length > 0">
                <tr v-for="(data,index) in datasets">
                  <!-- {{data}} -->
                  <template v-for="column in options.columns">
                    <td
                      :data-label="$t(column.title)"
                      :class="((index+2 === datasets.length || index+1 === datasets.length) && options.summary) ? {'font-weight-bold':options.summary, 'border-bottom-0':index+1 === datasets.length, 'text-right':includes(options.right_align, column.key) } : {'text-right':includes(options.right_align, column.key) || column.type === 'component' , 'table-col-truncate': column.type === 'text' && column.key === 'description'}"
                    >
                      <span
                        v-if="options.summary && (index+2 === datasets.length || index+1 === datasets.length)"
                      >{{data[column.key]}}</span>

                      <!--Check modifier function when the type is custom-->
                      <span
                        v-else-if="column.type === 'custom'"
                        v-html="column.modifier(data[column.key]).value"
                        :class="column.modifier(data[column.key]).class"
                      ></span>

                      <!-- Show serially when the column type is array -->
                      <span v-else-if="column.type === 'array'">
                        <span v-for="value in data[column.key]">
                          {{value}}
                          <br />
                        </span>
                      </span>
                      <span v-else>
                        <!--When the column type is clickable_link -->
                        <a
                          :href="publicPath+'/'+column.source"
                          v-if="column.type === 'clickable_link' && !column.uniquefield && !column.dynamicSource"
                        >{{data[column.key]}}</a>

                        <a
                          :href="publicPath+'/'+column.source+'/'+data[column.uniquefield]"
                          v-else-if="column.type === 'clickable_link' && column.uniquefield && data[column.uniquefield] && !column.dynamicSource"
                        >{{data[column.key]}}</a>

                        <a
                          :href="publicPath+'/'+data[column.dynamicSource]+'/'+data[column.uniquefield]"
                          v-else-if="column.type === 'clickable_link' && column.uniquefield && data[column.uniquefield] && column.dynamicSource"
                        >{{data[column.key]}}</a>

                        <!-- When the column type is text -->
                        <span
                          v-else
                          :class="{'table-col-truncate-item': column.type === 'text' && column.key === 'description'}"
                        >{{data[column.key]}}</span>
                      </span>

                      <!--When the column type is images-->
                      <span
                        v-if="(index+2 === datasets.length || index+1 === datasets.length) && options.summary"
                      ></span>
                      <span v-else>
                        <img
                          width="70px"
                          height="50px"
                          :src="publicPath+'/'+column.source+'/'+data[column.imagefield]"
                          v-if="column.type === 'images'"
                        />
                      </span>

                      <!-- Show serially when the column type is lang -->
                      <span v-if="column.type === 'language'">{{$t('lang.'+ data[column.key])}}</span>

                      <!-- Load component when the column type is component -->
                      <span v-if="column.type === 'component' && activeComponent">
                        <span v-if="column.modifier">
                          <component
                            v-if="column.modifier(data[column.key])"
                            v-bind:is="column.componentName"
                            :rowData="data"
                            @readData="readData"
                            :rowIndex="index"
                            @setPreLoader="setPreLoader"
                          ></component>
                        </span>
                        <span v-else>
                          <component
                            v-bind:is="column.componentName"
                            :rowData="data"
                            :rowIndex="index"
                            @readData="readData"
                            @setPreLoader="setPreLoader"
                          ></component>
                        </span>
                      </span>
                    </td>
                    <!-- For custom input field-->
                    <td
                      v-if="column.key == options.positionAfter"
                      v-for="(name,index) in columnName"
                    >
                      <span
                        v-if="customDateId.some(function(element) {return element == index &&  data[name] != ''})"
                      >{{datePickerDateFormatter(data[name])}}</span>
                      <span v-else>{{data[name]}}</span>
                    </td>
                  </template>
                </tr>
              </tbody>
              <tfoot v-if="total>0">
      
                 Total  {{total}}/OMR  
         
              </tfoot>
            </table>
          </div>

        </div>
        <div class="text-center" v-if="datasets.length>0 && showLoadMore">
          <div v-if="buttonPreloader" class="col s2 offset-s5 load-more-button">
            <pre-loader size="smaller-preloader"></pre-loader>
          </div>
          <load-more :buttonLoader="buttonLoader" :isDisabled="isDisabled" @submit="increaseLimit"></load-more>
        </div>
        <div class="row margin-fix" v-if="datasets.length<=0 || showEmptyTableText">
          <div class="col s12">
            <h6 class="text-center">{{ $t('lang.didnt_find') }}</h6>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosGetPost from "../../helper/axiosGetPostCommon";

export default {
  extends: axiosGetPost,
  props: ["options", "exportData", "exportFileName", "tab_name", "route_name"],
  data() {
    return {
      dateRangeCloseBtn: false,
      buttonLoader: false,
      isDisabled: false,
      dateClear: false,
      datasets: [],
      exportExcelData: [],
      exportExcelShow: false,
      exportDataSets: [],
      reqType: "",
      filtersData: [],
      selectedFiltersData: [],
      columnKey: "",
      columnSortedBy: "ASC",
      showEmptyTableText: false,
      paginationLimit: 20,
      paginationOffset: 0,
      totalResultCount: 0,
      showLoadMore: false,
      total: 0,
      searchValue: "",
      totalDataRow: [],
      alreadyGotRowSettings: false,
      paginationLimitIncreased: false,
      buttonPreloader: false,
      searchLoader: false,
      selectedColumn: "",
      hidePreLoader: false,
      filterColumn: "brand",
      allDataRows: [],
      activeComponent: true,
      columnName: [],
      //dropdown:false,
      findkey: (obj, key, value) => {
        _.findKey(obj, { key: value });
      },

      includes: (array, value) => {
        if (_.includes(array, value)) {
          return true;
        }
        return false;
      },
    };
  },
  watch: {
    exportData: function (newVal) {
      if (newVal) {
        this.exportReportData();
      }
    },
  },
  mounted() {
    let instance = this;
    if (instance.options.filters)
      instance.filtersData = instance.options.filters;
    // Search data from table
    instance.searchData();
    // Get Selected filters Data

    if (instance.filtersData) {
      instance.filtersData.forEach(function (element, i) {
        $("#filter_" + i).on("change", function () {
          let value = $(this).val(),
            key = element.key;
          /* Here dynamic column gets created when filter data changes*/
          instance.filterColumn = this.value;
          instance.$root.$emit("filter-data-option", {
            filterOption: instance.filterColumn,
          });
          if (
            value === "all" &&
            instance.selectedFiltersData.find((filter) => filter.key === key)
          ) {
            _.pull(
              instance.selectedFiltersData,
              instance.selectedFiltersData.find((filter) => filter.key === key)
            );
          } else if (
            instance.selectedFiltersData.find((filter) => filter.key === key)
          ) {
            instance.selectedFiltersData.find(
              (filter) => filter.key === key
            ).value = value;
          } else {
            instance.selectedFiltersData.push({ key, value });
          }

          instance.searchLoader = true;
          instance.paginationOffset = 0;
          instance.readData();
        });
      });
    }
    if (this.options.sortingOrder) {
      this.columnSortedBy = this.options.sortingOrder;
    }
    // Read data from DB, before that know settings data for max row limit
    instance.knowDefaultRowSettings();
    this.reloadData();
    this.updateDataAfterDelete();

    this.$hub.$on("deleteActionPreLoader", function (value) {
      instance.hidePreLoader = value;
    });
  },

  methods: {

    dateClose() {
      let instance = this;
      instance.selectedFiltersData = instance.selectedFiltersData.filter(
        function (element) {
          return element.filterKey !== "date_range";
        }
      );
      instance.readData();
      instance.dateRangeCloseBtn = false;
      instance.dateClear = true;
    },
    resetDateClears(value) {
      this.dateClear = false;
    },
    exportReportData() {
      this.reqType = "export";
      this.readData();
    },
    resetExcel(value) {
      this.exportExcelShow = value;
      this.$emit("resetStatus", false);
    },
    setId(id) {
      this.$emit("setId", id);
    },
    cancelBooking(id) {
      this.$emit("cancelBooking", id);
    },
    getDatefilterValue: function (fromDate, toDate, key) {
      let instance = this,
        filterKey = "date_range",
        value = [fromDate, toDate];

      if (instance.selectedFiltersData.find((filter) => filter.key === key)) {
        instance.selectedFiltersData.find(
          (filter) => filter.key === key
        ).value = value;
      } else {
        instance.selectedFiltersData.push({ filterKey, value });
      }
      instance.paginationOffset = 0;
      instance.searchLoader = true;
      instance.readData();
      instance.dateRangeCloseBtn = true;
    },
    changeSortingKey(newKey) {
      let instance = this;
      if (instance.columnKey == newKey) {
        if (instance.columnSortedBy == "ASC") instance.columnSortedBy = "DESC";
        else instance.columnSortedBy = "ASC";
      } else {
        instance.columnSortedBy = "ASC";
      }
      instance.columnKey = newKey;
      instance.paginationOffset = 0;
      instance.searchLoader = true;
      instance.readData();
    },
    knowDefaultRowSettings() {
      let instance = this;
      if (!instance.alreadyGotRowSettings) {
        instance.paginationOffset = 0; // Initialize offset limit also
        instance.paginationLimit = instance.defaultRowSetting;
        if (instance.options.pagination) {
          if (instance.options.pagination.limit)
            instance.paginationLimit = instance.options.pagination.limit;
        }
        if (!instance.paginationLimit) instance.paginationLimit = 100;
        instance.alreadyGotRowSettings = true;
        instance.readData();
      }
    },

    increaseLimit() {
      this.buttonLoader = true;
      this.isDisabled = true;
      this.paginationOffset += Number(this.paginationLimit);
      this.paginationLimitIncreased = true;
      this.readData();
    },
    searchData() {
      let instance = this,
        timer;
      if (instance.options.search) {
        $("#search").on("input", function () {
          let value = $(this).val();
          instance.searchValue = value;
          instance.showLoadMore = false;
          instance.paginationOffset = 0;
          if (instance.searchValue) {
            if (timer) {
              clearTimeout(timer);
            }

            timer = setTimeout(function () {
              instance.searchLoader = true;
              instance.readData();
            }, 400);
          } else {
            instance.searchLoader = true;
            instance.readData();
          }
        });
      }
    },
    initOffsetAndLimit() {
      this.paginationOffset = 0;
      this.alreadyGotRowSettings = false;
      this.knowDefaultRowSettings();
    },
    updateDataAfterEdit(id, newData) {
      let instance = this;
      instance.datasets.push(newData);
    },

    updateDataAfterDelete() {
      let instance = this;
      this.$hub.$on("updateDataAfterDelete", function (index) {
        instance.datasets.splice(index, 1);
      });
    },
    reloadData() {
      let instance = this;
      this.$hub.$on("reloadDataTable", function (value = true) {
        if (value) {
          instance.paginationLimit = 10;
          instance.paginationOffset = 0;
          instance.activeComponent = false;
          instance.readData();
        }
      });
    },

    readData() {
      let instance = this,
        url = instance.options.source;
      // Know which field to be sorted
      if (!instance.columnKey) {
        if (instance.options.sortedBy) {
          instance.columnKey = instance.options.sortedBy;
          instance.columnSortedBy = instance.options.sortedType;
          this.selectedColumn = instance.options.sortedBy;
        } else if (
          instance.options.columns &&
          !_.isEmpty(instance.options.columns)
        ) {
          instance.columnKey = instance.options.columns[0].key;
          this.selectedColumn = instance.options.columns[0].key;
        }
      }
      if (
        !instance.searchLoader &&
        !instance.buttonPreloader &&
        instance.reqType === "" &&
        !instance.paginationLimitIncreased
      ) {
        instance.setPreLoader(false);
      }
      instance.axiosPost(
        url,
        {
          columnKey: instance.columnKey,
          columnSortedBy: instance.columnSortedBy,
          rowOffset: instance.paginationOffset,
          rowLimit: instance.paginationLimit,
          filtersData: instance.selectedFiltersData,
          searchValue: instance.searchValue,
          reqType: instance.reqType,
        },
        function (response) {
          let a = _.clone(response.data);
      
          instance.$hub.$emit("dataRowsForTable", response.data.datarows);
          if (response.data.allProducts) {
            instance.allDataRows = response.data.allProducts;
          }
          if (instance.reqType === "") {
            instance.customDateId = response.data.customDateId;
            instance.columnName = response.data.columnName;
            instance.total = response.data.total;
            instance.searchLoader = false;
            if (instance.paginationLimitIncreased) {
              let newData = response.data.datarows;
              if (instance.options.summary) {
                instance.datasets = instance.datasets.filter(function (
                  element
                ) {
                  return (
                    element[instance.options.summationKey] !== "Total" &&
                    element[instance.options.summationKey] !== "Grand Total"
                  );
                });
                instance.options.summation.forEach(function (name) {
                  let match = _.find(newData, function (item) {
                    return item[instance.options.summationKey] === "Total";
                  });
                  if (match) {
                    match[name] = match[name] + instance.totalDataRow[name];
                    instance.totalDataRow[name] = match[name];
                  }
                });
              }
              newData.forEach(function (element) {
                if (instance.options.formatting) {
                  if (
                    instance.options.zero_format == true &&
                    element.cash_register_opening_amount == 0 &&
                    element.cash_register_closing_amount == null
                  ) {
                    if (element.opening_amount == 0) {
                      element["opening_amount"] = instance.zeroFormatForOpening(
                        element.opening_amount
                      );
                      element[
                        "cash_receives"
                      ] = instance.numberFormatSalonGeneralBooking(
                        element.cash_receives
                      );
                      element[
                        "cash_sales"
                      ] = instance.numberFormatSalonGeneralBooking(
                        element.cash_sales
                      );
                    }
                  } else if (instance.options.zero_format) {
                    instance.options.formatting.forEach(function (key) {
                      element[key] = instance.zeroFormat(
                        element[key],
                        element.cash_register_closing_amount
                      );
                      element[
                        "cash_receives"
                      ] = instance.numberFormatSalonGeneralBooking(
                        element.cash_receives
                      );
                      element[
                        "cash_sales"
                      ] = instance.numberFormatSalonGeneralBooking(
                        element.cash_sales
                      );
                    });
                  } else {
                    instance.options.formatting.forEach(function (key) {
                      element[key] = instance.numberFormatSalonGeneralBooking(
                        element[key]
                      );
                    });
                  }
                }
                if (instance.options.dateFormatting) {
                  instance.options.dateFormatting.forEach(function (key) {
                    if (element[key] !== undefined)
                      element[key] = instance.dateFormatBooking(element[key]);
                  });
                }
                if (instance.options.percentFormatting) {
                  instance.options.percentFormatting.forEach(function (key) {
                    if (element[key] !== undefined)
                      element[key] = instance.percentFormatting(element[key]);
                  });
                }
              });
              if (newData.length > 0) {
                newData.forEach(function (e) {
                  instance.datasets.push(e);
                });
              }

              instance.isDisabled = false;
              instance.buttonLoader = false;
              instance.printData();
            } else {
              if (instance.options.summary) {
                instance.totalDataRow = _.clone(
                  response.data.datarows.find(
                    (key) => key[instance.options.summationKey] === "Total"
                  )
                );
              }

              instance.formatting(response.data.datarows);
              instance.printData();
            }
            instance.totalResultCount = response.data.count;

            setTimeout(function () {
              instance.activeComponent = true;
              instance.setPreLoader(true);
            }, 200);

            if (instance.buttonPreloader) instance.buttonPreloader = false;
            if (instance.options.summary) {
              instance.totalResultCount += 2;
            }

            if (instance.totalResultCount > instance.datasets.length)
              instance.showLoadMore = true;
            else instance.showLoadMore = false;

            instance.paginationLimitIncreased = false;
            $(".tooltipped").tooltip();
          } else {
            instance.exportExcelData = response.data.datarows;
            instance.exportExcelShow = true;
            instance.reqType = "";
          }

          instance.$emit("printBarcode");
        },
        function (error) {
          instance.searchLoader = false;
          instance.setPreLoader(true);
        }
      );
    },
    formatting(dataRows) {
      let instance = this;
      instance.datasets = dataRows;
      instance.datasets.forEach(function (element) {
        if (
          instance.options.zero_format == true &&
          element.cash_register_opening_amount == 0 &&
          element.cash_register_closing_amount == null
        ) {
          if (element.opening_amount == 0) {
            element["opening_amount"] = instance.zeroFormatForOpening(
              element.opening_amount
            );
            element["cash_receives"] = instance.numberFormatSalonGeneralBooking(
              element.cash_receives
            );
            element["cash_sales"] = instance.numberFormatSalonGeneralBooking(
              element.cash_sales
            );
          }
        } else if (instance.options.zero_format == true) {
          if (instance.options.formatting) {
            instance.options.formatting.forEach(function (key) {
              element[key] = instance.zeroFormat(
                element[key],
                element.cash_register_closing_amount
              );
              element[
                "cash_receives"
              ] = instance.numberFormatSalonGeneralBooking(
                element.cash_receives
              );
              element["cash_sales"] = instance.numberFormatSalonGeneralBooking(
                element.cash_sales
              );
            });
          }
        } else {
          if (instance.options.formatting) {
            instance.options.formatting.forEach(function (key) {
              element[key] = instance.numberFormatSalonGeneralBooking(
                element[key]
              );
            });
          }
        }
        if (instance.options.dateFormatting) {
          instance.options.dateFormatting.forEach(function (key) {
            if (element[key] !== undefined)
              element[key] = instance.dateFormatBooking(element[key]);
          });
        }

        if (instance.options.percentFormatting) {
          instance.options.percentFormatting.forEach(function (key) {
            if (element[key] !== undefined)
              element[key] = instance.percentFormatting(element[key]);
          });
        }
      });
    },
    printData() {
      this.$emit("printData", this.allDataRows);
    },
  },
};
</script>