import React from "react";
import { Table } from "react-bootstrap";

// Initialize the required modules

function TableTotalFamiliesServed(props) {
  const { title } = props;
 

  return (
    <div>
      <Table className="table_custom_div" hover>
        <thead>
          <tr>
            <th colSpan={2}>{props.title}</th>

          </tr>
        </thead>
        <tbody>
          
          <tr>
            <td>Total Families Submitted</td>
            <td>135</td>
          </tr>
          <tr>
            <td>Total Families Paid</td>
            <td>120</td>
          </tr>
          <tr>
            <td>Total Applications Submitted</td>
            <td> 10,79,93,015.00 </td>
          </tr>
          <tr>
            <td>Average Case Size</td>
            <td> 8,99,941.79 </td>
          </tr>
          <tr>
            <td>Potential Assets at First</td>
            <td> 52,50,000 </td>
          </tr>
          <tr>
            <td>Total New Families</td>
            <td>3</td>
          </tr>
          <tr>
            <td>Closing Ratio</td>
            <td>27%</td>
          </tr>
        </tbody>
      </Table>
    </div>
  );
}

export default TableTotalFamiliesServed;
