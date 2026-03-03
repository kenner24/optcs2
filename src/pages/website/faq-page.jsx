import { useEffect, useState } from "react";
import { useGetPageContentQuery } from "../../redux/api/website/page-content-api";
import FaqCard from "../../Components/faq/faq-card";
import { PageTypeConstant } from "../../constants/pageType.constant";

const defaultFaqs = [
  {
    id: "faq-1",
    question: "What is OPTCS?",
    content: "OPTCS is a business reporting and analytics platform that connects to your existing tools like QuickBooks and Google Sheets, then consolidates your data into a single dashboard. You get real-time reports across sales, marketing, financial, and operations categories without the manual spreadsheet work."
  },
  {
    id: "faq-2",
    question: "What data sources does OPTCS connect to?",
    content: "OPTCS currently integrates with QuickBooks and Google Sheets. We are actively building connectors for additional platforms. If you have a specific integration need, reach out to our team through the Contact Us page."
  },
  {
    id: "faq-3",
    question: "How long does it take to set up?",
    content: "Most users are up and running in under 15 minutes. You create an account, connect your data sources through our guided connector setup, and your dashboards begin populating automatically. No technical background required."
  },
  {
    id: "faq-4",
    question: "Is my data secure?",
    content: "Yes. OPTCS uses secure OAuth connections to access your data, which means we never store your login credentials for third-party services. All data is encrypted in transit and at rest. We follow industry best practices for data protection and access control."
  },
  {
    id: "faq-5",
    question: "Can I control who sees what on my team?",
    content: "Absolutely. OPTCS includes a staff management system with role-based permissions. You can add team members and assign specific access levels so each person sees only the reports and data relevant to their role."
  },
  {
    id: "faq-6",
    question: "What types of reports are available?",
    content: "OPTCS provides four categories of reports: <strong>Sales</strong> (open opportunities, submitted production, pending business, paid annuity, YTD comparisons), <strong>Marketing</strong> (lead generation, seminar response rates, cost per client, funnel performance), <strong>Financial</strong> (cash on hand, monthly expenses, profitability, cash flow forecasts), and <strong>Operations</strong> (days to issue, review preparation, new assets from existing clients)."
  },
  {
    id: "faq-7",
    question: "Is there a free trial?",
    content: "Yes. You can sign up for a free trial to explore the platform, connect your data sources, and see your dashboards in action before committing to a paid plan. No credit card is required to start."
  },
  {
    id: "faq-8",
    question: "How do I get support if I need help?",
    content: "You can reach our support team through the Contact Us page on this website. We also provide in-app guidance and documentation to help you get the most out of OPTCS. Our team typically responds within one business day."
  },
  {
    id: "faq-9",
    question: "Can I export or share my reports?",
    content: "Yes. OPTCS is built with sharing in mind. You can generate professional reports and share them with team members, clients, or partners directly from the platform."
  },
  {
    id: "faq-10",
    question: "Does OPTCS work for my industry?",
    content: "OPTCS is designed to be flexible across industries. It is especially well-suited for financial advisory practices, insurance agencies, consulting firms, and any business that tracks sales pipelines, marketing funnels, and financial performance. If your business runs on data, OPTCS can help you see it clearly."
  }
];

function FaqPage() {
  const { data } = useGetPageContentQuery(PageTypeConstant.FAQ);
  const [faqContent, setFaqContent] = useState(defaultFaqs);

  useEffect(() => {
    const content = data?.data?.content;
    if (content?.length > 0) {
      setFaqContent(content);
    }
  }, [data]);

  return (
    <>
      <section className="banner_section banner_main_outer terms_banner">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-8 offset-md-2 col-sm-12">
              <div className="banner_left text-center">
                <h1 className="mb-3">Frequently Asked
                  <span> Questions</span></h1>
                <p className="mb-4">Everything you need to know about getting started with OPTCS and making the most of the platform.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="google_sheet_outer">
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <div id="main" className="custom_accordian faq_accordian_div">
                <div className="accordion" id="faq">
                  {
                    faqContent?.length > 0 &&
                    faqContent.map(
                      (value) => <FaqCard
                        key={value?.id}
                        answer={value?.content}
                        question={value?.question}
                        queNum={value?.id}
                      ></FaqCard>
                    )
                  }
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default FaqPage;
