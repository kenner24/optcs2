import Tab from "react-bootstrap/Tab";
import Tabs from "react-bootstrap/Tabs";
import AccountDetail from "../../Components/settings/account-detail";
import ChangePassword from "../../Components/settings/change-password";
import GoalSetting from "../../Components/settings/goal-setting";

function SettingsPage() {
  return (
    <Tabs
      defaultActiveKey="changePassword"
      id="uncontrolled-tab-example"
      className="mb-3"
      fill
    >
      <Tab eventKey="changePassword" title="Change Password">
        <ChangePassword></ChangePassword>
      </Tab>
      <Tab eventKey="accountDetails" title="Account Details">
        <AccountDetail></AccountDetail>
      </Tab>
      <Tab eventKey="goalSetting" title="Goal Settings">
        <GoalSetting></GoalSetting>
      </Tab>
    </Tabs>
  )
}

export default SettingsPage;