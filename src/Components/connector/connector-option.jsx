import Tab from "react-bootstrap/Tab";
import Tabs from "react-bootstrap/Tabs";
import GoogleSheetOption from "./google/google-sheet-option";
import QuickBookOption from "./quickbook/quick-book-option";
import { useFetchPreferenceQuery } from "../../redux/api/connector/connector-api";

const ConnectorOption = ({ userDetails }) => {
  const { data: preferences } = useFetchPreferenceQuery();

  return (
    <Tabs
      defaultActiveKey="google-sheet"
      id="connector-tabs"
      className="mt-5"
      fill
    >
      <Tab eventKey="google-sheet" title="Google Sheet">
        {
          userDetails?.google_sheet_access ?
            <GoogleSheetOption preferences={preferences} /> :
            <h4 className="mt-3">Google Sheet not connected</h4>
        }
      </Tab>
      <Tab eventKey="quick-book" title="Quick Book">
        {
          userDetails?.quick_books_access ?
            <QuickBookOption preferences={preferences} /> :
            <h4 className="mt-3">Quick book not connected</h4>
        }
      </Tab>
    </Tabs>
  )
}

export default ConnectorOption;