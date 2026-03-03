import { useEffect, useState } from "react";
import { useGetPageContentQuery } from "../../redux/api/website/page-content-api";
import { PageTypeConstant } from "../../constants/pageType.constant";

const defaultTermsOfUse = `
<div style="text-align: left; color: #6E7D8C; line-height: 1.8;">
  <p style="margin-bottom: 10px; color: #999;"><em>Last updated: March 1, 2026</em></p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">1. Acceptance of Terms</h4>
  <p>By accessing or using the OPTCS platform ("Service"), you agree to be bound by these Terms of Use. If you do not agree to these terms, you may not use the Service. These terms apply to all users, including individual account holders and team members added by an account holder.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">2. Description of Service</h4>
  <p>OPTCS is a business reporting and analytics platform that connects to third-party data sources (such as QuickBooks and Google Sheets) to provide consolidated dashboards and reports. The Service includes features for sales tracking, marketing analytics, financial reporting, operations metrics, staff management, and data connector management.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">3. Account Registration</h4>
  <p>To use the Service, you must create an account by providing accurate and complete registration information. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You must notify us immediately of any unauthorized use of your account.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">4. Data and Connectors</h4>
  <p>When you connect third-party data sources to OPTCS, you authorize us to access and retrieve data from those sources on your behalf for the purpose of generating reports and dashboards. You represent that you have the authority to grant this access and that doing so does not violate any agreements you have with those third-party services. You may disconnect any data source at any time through your account settings.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">5. Acceptable Use</h4>
  <p>You agree to use the Service only for lawful purposes and in accordance with these Terms. You may not:</p>
  <ul style="padding-left: 20px; margin-bottom: 15px;">
    <li>Use the Service to violate any applicable laws or regulations</li>
    <li>Attempt to gain unauthorized access to any part of the Service or its systems</li>
    <li>Interfere with or disrupt the Service or servers connected to it</li>
    <li>Reverse engineer, decompile, or disassemble any part of the Service</li>
    <li>Use the Service to transmit any malicious code or harmful content</li>
    <li>Share your account credentials with unauthorized individuals</li>
  </ul>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">6. Staff and Permissions</h4>
  <p>If you add staff members to your account, you are responsible for managing their access levels and permissions. You are also responsible for any actions taken by staff members within your account. OPTCS provides role-based access controls, but the configuration and management of these permissions is your responsibility.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">7. Intellectual Property</h4>
  <p>The Service, including its design, features, code, documentation, and branding, is owned by OPTCS and protected by intellectual property laws. Your use of the Service does not grant you any ownership rights to the Service or its components. Your business data remains your property at all times.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">8. Service Availability</h4>
  <p>We strive to maintain high availability of the Service but do not guarantee uninterrupted access. The Service may be temporarily unavailable due to maintenance, updates, or circumstances beyond our control. We will make reasonable efforts to notify you of planned downtime in advance.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">9. Limitation of Liability</h4>
  <p>To the maximum extent permitted by law, OPTCS shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the Service. This includes, but is not limited to, damages for loss of profits, data, or business opportunities. Our total liability for any claim related to the Service shall not exceed the amount you paid for the Service in the twelve months preceding the claim.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">10. Termination</h4>
  <p>You may close your account at any time through your account settings or by contacting us. We reserve the right to suspend or terminate your account if you violate these Terms. Upon termination, your right to use the Service ceases immediately, and we will delete your data in accordance with our Privacy Policy.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">11. Changes to Terms</h4>
  <p>We may update these Terms of Use from time to time. We will notify you of material changes by posting a notice on the platform or by email. Your continued use of the Service after changes are posted constitutes your acceptance of the revised terms.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">12. Governing Law</h4>
  <p>These Terms shall be governed by and construed in accordance with the laws of the jurisdiction in which OPTCS operates, without regard to conflict of law principles.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">13. Contact</h4>
  <p>If you have any questions about these Terms of Use, please contact us at <strong>support@optcs.com</strong>.</p>
</div>
`;

function TermsConditionPage() {
  const { data } = useGetPageContentQuery(PageTypeConstant.TERMS_OF_USE);
  const [termOfUse, setTermOfUse] = useState(defaultTermsOfUse);

  useEffect(() => {
    let finalPayload = "";
    const content = data?.data?.content;
    if (content?.length > 0) {
      for (const iterator of content) {
        finalPayload += iterator?.content;
      }
      setTermOfUse(finalPayload);
    }
  }, [data]);

  return (
    <>
      <section className="banner_section banner_main_outer terms_banner">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-8 offset-md-2 col-sm-12">
              <div className="banner_left text-center">
                <h1 className="mb-3">Terms of
                  <span> Use</span></h1>
                <p className="mb-4">Please read these terms carefully before using the OPTCS platform.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="google_sheet_outer">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-12" dangerouslySetInnerHTML={{ __html: termOfUse }}>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default TermsConditionPage;
