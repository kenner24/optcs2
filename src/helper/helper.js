import moment from "moment";

export function firstLetterUpperCase(str) {
  return str.charAt(0).toUpperCase() + str.slice(1);
}

export function getYearList(startYear) {
  const endYear = moment().add(10, "years").format("YYYY");
  const yearArray = [];
  for (let index = startYear; index <= endYear; index++) {
    yearArray.push({
      label: index,
      value: index
    });

  }
  return yearArray;
}