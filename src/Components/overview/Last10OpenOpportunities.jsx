import React, { useEffect, useState } from "react";
import moment from "moment";
import { Table } from "react-bootstrap";
import { useGetOverviewPageDataQuery } from "../../redux/api/overview/overview-api";

function Last10OpenOpportunities() {
  const { data } = useGetOverviewPageDataQuery(moment().format("YYYY"));
  const [last10Opportunities, setLast10Opportunities] = useState([]);

  useEffect(() => {
    if (data?.success) {
      if (data?.data?.last_10_opportunity) {
        setLast10Opportunities(data?.data?.last_10_opportunity);
      }
    }
  }, [data]);

  return (
    <div>
      <Table className="table_custom_div" hover>
        <thead>
          <tr>
            <th colSpan={2} >Last 10 Open Opportunities</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>Opportunity Name</b></td>
            <td><b>Amount</b></td>
          </tr>
          {
            last10Opportunities.map(ele => {
              return (
                <tr key={`last_10_opp_${ele?.id}`}>
                  <td>{ele?.name}</td>
                  <td>${ele?.amount}</td>
                </tr>
              );
            })
          }
        </tbody>
      </Table>
    </div>
  );
}

export default Last10OpenOpportunities;
