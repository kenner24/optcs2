import { useEffect, useState } from "react";
import { useGetPageContentQuery } from "../../redux/api/website/page-content-api";
import { PageTypeConstant } from "../../constants/pageType.constant";

const defaultPrivacyPolicy = `
<div style="text-align: left; color: #6E7D8C; line-height: 1.8;">
  <p style="margin-bottom: 10px; color: #999;"><em>Last updated: March 1, 2026</em></p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">1. Introduction</h4>
  <p>OPTCS ("we," "our," or "us") is committed to protecting the privacy of our users. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform and website.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">2. Information We Collect</h4>
  <p><strong>Account Information:</strong> When you register for an account, we collect your name, email address, and password. If you sign up using Google authentication, we receive your basic profile information from Google.</p>
  <p><strong>Business Data:</strong> When you connect data sources such as QuickBooks or Google Sheets, we access the specific data needed to generate your reports and dashboards. We do not store your login credentials for these third-party services. We use secure OAuth connections to access your data.</p>
  <p><strong>Usage Data:</strong> We collect information about how you interact with our platform, including pages visited, features used, and time spent on the platform.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">3. How We Use Your Information</h4>
  <p>We use the information we collect to:</p>
  <ul style="padding-left: 20px; margin-bottom: 15px;">
    <li>Provide, maintain, and improve our platform and services</li>
    <li>Generate reports and dashboards based on your connected data sources</li>
    <li>Manage your account and provide customer support</li>
    <li>Send you important service updates and communications</li>
    <li>Detect, prevent, and address technical issues and security threats</li>
  </ul>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">4. Data Security</h4>
  <p>We implement industry-standard security measures to protect your data. All data transmitted between your browser and our servers is encrypted using TLS. Data at rest is encrypted using AES-256 encryption. We conduct regular security assessments and maintain strict access controls for our team.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">5. Third-Party Services</h4>
  <p>OPTCS connects to third-party services such as QuickBooks and Google Sheets to retrieve your business data. These connections are made using secure OAuth protocols, meaning we never see or store your passwords for these services. Each third-party service has its own privacy policy, and we encourage you to review them.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">6. Data Sharing</h4>
  <p>We do not sell, trade, or rent your personal information or business data to third parties. We may share information only in the following circumstances:</p>
  <ul style="padding-left: 20px; margin-bottom: 15px;">
    <li>With your explicit consent</li>
    <li>To comply with legal obligations or valid legal requests</li>
    <li>To protect our rights, privacy, safety, or property</li>
    <li>With service providers who assist in operating our platform, subject to confidentiality agreements</li>
  </ul>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">7. Data Retention</h4>
  <p>We retain your account information and connected business data for as long as your account is active. If you delete your account, we will remove your personal information and business data from our systems within 30 days, except where retention is required by law.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">8. Your Rights</h4>
  <p>You have the right to access, correct, or delete your personal information at any time through your account settings. You can disconnect any data source at any time, which will stop our access to that data. You may also request a copy of your data or ask us to delete your account entirely by contacting us at support@optcs.com.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">9. Cookies</h4>
  <p>We use cookies and similar technologies to maintain your session, remember your preferences, and improve your experience on our platform. You can control cookie settings through your browser, though disabling cookies may affect platform functionality.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">10. Changes to This Policy</h4>
  <p>We may update this Privacy Policy from time to time. We will notify you of any significant changes by posting a notice on our platform or sending you an email. Your continued use of OPTCS after changes are posted constitutes your acceptance of the revised policy.</p>

  <h4 style="color: #000; font-weight: 500; margin: 25px 0 10px;">11. Contact Us</h4>
  <p>If you have any questions about this Privacy Policy or our data practices, please contact us at <strong>support@optcs.com</strong>.</p>
</div>
`;

function PrivacyPolicyPage() {
  const { data } = useGetPageContentQuery(PageTypeConstant.PRIVACY_POLICY);
  const [privacyPolicy, setPrivacyPolicy] = useState(defaultPrivacyPolicy);

  useEffect(() => {
    let finalPayload = "";
    const content = data?.data?.content;
    if (content?.length > 0) {
      for (const iterator of content) {
        finalPayload += iterator?.content;
      }
      setPrivacyPolicy(finalPayload);
    }
  }, [data]);

  return (
    <>
      <section className="banner_section banner_main_outer terms_banner">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-8 offset-md-2 col-sm-12">
              <div className="banner_left text-center">
                <h1 className="mb-3">Privacy
                  <span> Policy</span></h1>
                <p className="mb-4">How we collect, use, and protect your information when you use OPTCS.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="google_sheet_outer">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-12" dangerouslySetInnerHTML={{ __html: privacyPolicy }}>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default PrivacyPolicyPage;
