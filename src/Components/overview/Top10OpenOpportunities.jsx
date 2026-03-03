import React, { useEffect, useState } from "react";
import moment from "moment";
import { Table } from "react-bootstrap";
import { useGetOverviewPageDataQuery } from "../../redux/api/overview/overview-api";

function Top10OpenOpportunities() {
  const { data } = useGetOverviewPageDataQuery(moment().format("YYYY"));
  const [top10Opportunities, setTop10Opportunities] = useState([]);

  useEffect(() => {
    if (data?.success) {
      if (data?.data?.top_10_opportunity) {
        setTop10Opportunities(data?.data?.top_10_opportunity);
      }
    }
  }, [data]);

  return (
    <div>
      <Table className="table_custom_div" hover>
        <thead>
          <tr>
            <th colSpan={2} >Top 10 Open Opportunities</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><b>Opportunity Name</b></td>
            <td><b>Amount</b></td>
          </tr>
          {
            top10Opportunities.map(ele => {
              return (
                <tr key={`top_10_opp_${ele?.id}`}>
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

export default Top10OpenOpportunities;
