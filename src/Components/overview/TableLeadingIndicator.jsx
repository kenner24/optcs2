import React from "react";
import { Table } from "react-bootstrap";

// Initialize the required modules

function OverviewLeadingIndicator(props) {
  const { title } = props;


  return (
    <div>
      <Table className="table_custom_div production_table indicator_table" hover>
        <thead>
          <tr>
            <th colSpan={4}>{props.title}</th>
          </tr>
        </thead>
        <tbody>
          {/* <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style={{ background: "yellow" }} >Insert Date</td>
                    </tr> */}
          <tr>
            <td><b>Dept Leading KPIs Indicators as of</b></td>
            {/* <td><b>as of</b></td> */}
            <td className="insert_text" colSpan={3}>(Insert Date)&nbsp;17-02-2023</td>
          </tr>
          <tr>
            <td></td>
            <td className="varinace_text"><b>Actual</b></td>
            <td className="varinace_text"><b>Goal</b></td>
            <td className="varinace_text"><b>Variance</b></td>
          </tr>
          <tr className="marketing_td">
            <td ><b>Sales</b></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Open Opportunities</td>
            <td>  5,25,465  </td>
            <td>   2,50,000   </td>
            <td>   2,75,465   </td>
          </tr>
          <tr>
            <td>1st to 2nd</td>
            <td> 60%</td>
            <td> 65%  </td>
            <td>-5% </td>
          </tr>
          <tr>
            <td>Closing Ratio</td>
            <td> 20%</td>
            <td> 33%  </td>
            <td>-13% </td>
          </tr>
          <tr>
            <td>Annuity % of Business</td>
            <td> 42%</td>
            <td> 40%  </td>
            <td>2% </td>
          </tr>
          <tr>
            <td># of Clients Helped</td>
            <td>15</td>
            <td>20</td>
            <td></td>
          </tr>
          {/* <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr> */}
        </tbody>
        <tbody>
          <tr className="marketing_td">
            <td><b>Marketing</b></td>
            <td></td>
            <td></td>
            <td></td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>Stick</td>
            <td> 75%</td>
            <td> 75%</td>
            <td> 0% </td>
          </tr>
          <tr>
            <td>Upcoming Marketing Events</td>
            <td>4</td>
            <td>4 </td>
            <td> </td>
          </tr>
          <tr>
            <td>Avg Response Rates</td>
            <td>0.78%</td>
            <td>1%</td>
            <td>-0.22%</td>
          </tr>
          <tr>
            <td>Avg Cost Per Client</td>
            <td> 6,800 </td>
            <td> 4,500 </td>
            <td> (2,300)</td>
          </tr>
          <tr>
            <td>Avg. 1st Appts Scheduled Per Week</td>
            <td> 19 </td>
            <td>25</td>
            <td>6</td>
          </tr>
          {/* <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr> */}
        </tbody>

        <tbody>
          <tr className="marketing_td">
            <td><b>Servicing</b></td>
            <td></td>
            <td></td>
            <td></td>
            {/* <td></td> */}
          </tr>
          <tr>
            <td>Pending Business </td>
            <td>  24,58,036 </td>
            <td>  30,00,000 </td>
            <td style={{ color: "red" }} > (5,41,964)</td>
          </tr>
          <tr>
            <td>YTD Service Appts held</td>
            <td></td>
            <td> </td>
            <td> </td>
          </tr>
          <tr>
            <td>Avg Days to Issue</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
                  
          {/* <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr> */}
        </tbody>                
      </Table>
    </div>
  );
}

export default OverviewLeadingIndicator;
