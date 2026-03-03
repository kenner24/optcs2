import React from "react";
import { Table } from "react-bootstrap";

// Initialize the required modules

function TableFirstTable(props) {
  return (
    <div>
      <Table className="table_custom_div" hover>
        <thead>
          <tr>
            <th   colSpan={2} >{props.title}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>Start of week</b></td>
            <td><b>End of week</b></td>
          </tr>
          {/* <tr>
            <td></td>
            <td></td>

          </tr> */}
          <tr>
            <td>Total New Leads (All Funnels)</td>
            <td>20</td>
          </tr>
          <tr>
            <td>Total 1st Scheduled</td>
            <td>15</td>
          </tr>
          <tr>
            <td>1st Appt - Completed</td>
            <td>11</td>
          </tr>
          <tr>
            <td>1st Appt Show Ratio</td>
            <td>73%</td>
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

export default TableFirstTable;
