import React from "react";
import { Table } from "react-bootstrap";

// Initialize the required modules

function OverviewProduction(props) {
  const { title } = props;

  return (
    <div>
      <Table className="table_custom_div production_table" hover>
        <thead>
          <tr>
            <th colSpan={4}>{props.title}</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td></td>
            <td><b>Submitted</b></td>
            <td className="varinace_text"><b>Paid</b></td>
            <td className="varinace_text"><b>YTD</b></td>
            {/* <td></td> */}
          </tr>

          <tr>
            <td>Annuity</td>
            <td> 4,58,90,000.00 </td>
            <td> 3,84,58,746.00 </td>
            <td> 4,58,90,000.00 </td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>% of Business</td>
            <td>42%</td>
            <td>36%</td>
            <td>42%</td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>AUM</td>
            <td> 6,14,54,863.00 </td>
            <td> 5,12,54,630.00 </td>
            <td> 6,14,54,863.00 </td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>% of Business</td>
            <td>57%</td>
            <td>47%</td>
            <td>57%</td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>Other Business Line</td>
            <td> 6,48,152.00 </td>
            <td> 5,24,568.00 </td>
            <td> 6,48,152.00 </td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>% of Business</td>
            <td> 1%</td>
            <td>0% </td>
            <td>1% </td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>Total</td>
            <td>  10,79,93,015.00 </td>
            <td> 9,02,37,944.00  </td>
            <td> 10,79,93,015.00 </td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>Families Served</td>
            <td>  135 </td>
            <td> 0 </td>
            <td> 0</td>
            {/* <td></td> */}
          </tr>
        </tbody>
      </Table>
    </div>
  );
}

export default OverviewProduction;
