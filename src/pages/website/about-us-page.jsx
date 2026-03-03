import React, { useEffect, useState } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { useGetPageContentQuery } from "../../redux/api/website/page-content-api";
import { PageTypeConstant } from "../../constants/pageType.constant";

const defaultAboutContent = `
<div style="text-align: left;">
  <h3 style="color: #000; font-weight: 500; margin-bottom: 15px;">Who We Are</h3>
  <p style="color: #6E7D8C; line-height: 1.8; margin-bottom: 25px;">
    OPTCS was built for business owners, financial advisors, and practice managers who are tired of stitching together
    reports from five different platforms. We believe that understanding your business should not require a data science
    degree or hours of manual spreadsheet work.
  </p>
  <h3 style="color: #000; font-weight: 500; margin-bottom: 15px;">Our Mission</h3>
  <p style="color: #6E7D8C; line-height: 1.8; margin-bottom: 25px;">
    We make business intelligence accessible. OPTCS gives small and mid-sized businesses the same real-time reporting
    capabilities that large enterprises take for granted, without the enterprise price tag or complexity. Our platform
    connects to the tools you already use and turns scattered data into clear, actionable dashboards.
  </p>
  <h3 style="color: #000; font-weight: 500; margin-bottom: 15px;">What We Do</h3>
  <p style="color: #6E7D8C; line-height: 1.8; margin-bottom: 10px;">
    OPTCS is a centralized reporting and analytics platform that pulls data from QuickBooks, Google Sheets, and other
    business tools into one unified dashboard. From sales pipeline tracking to cash flow forecasting, from marketing
    funnel analysis to operations metrics, we give you a single source of truth for your entire business.
  </p>
  <ul style="color: #6E7D8C; line-height: 2; margin-bottom: 25px; padding-left: 20px;">
    <li><strong>Sales Reporting</strong> &mdash; Track open opportunities, submitted production, pending business, and year-to-date comparisons</li>
    <li><strong>Marketing Analytics</strong> &mdash; Monitor lead generation, seminar response rates, cost per client, and funnel performance</li>
    <li><strong>Financial Dashboards</strong> &mdash; See cash on hand, monthly expenses, profitability, and cash flow forecasts at a glance</li>
    <li><strong>Operations Tracking</strong> &mdash; Measure days to issue, review preparation, and growth from existing clients</li>
  </ul>
  <h3 style="color: #000; font-weight: 500; margin-bottom: 15px;">Why Businesses Choose OPTCS</h3>
  <p style="color: #6E7D8C; line-height: 1.8;">
    Setup takes minutes, not months. Our connector system links directly to your existing data sources, so there is
    no re-entry, no migration, and no disruption to your workflow. You get professional-grade reporting from day one,
    with a team and staff management system that lets you control exactly who sees what.
  </p>
</div>
`;

function AboutUsPage() {
  const { data } = useGetPageContentQuery(PageTypeConstant.ABOUT_US);
  const [aboutUsContent, setAboutUsContent] = useState(defaultAboutContent);

  useEffect(() => {
    let finalPayload = "";
    const content = data?.data?.content;
    if (content?.length > 0) {
      for (const iterator of content) {
        finalPayload += iterator?.content;
      }
      setAboutUsContent(finalPayload);
    }
  }, [data]);

  return (
    <>
      <section className="banner_section banner_main_outer terms_banner">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-8 offset-md-2 col-sm-12">
              <div className="banner_left text-center">
                <h1 className="mb-3">About
                  <span> Us</span></h1>
                <p className="mb-4">The reporting platform built for businesses that want clarity without complexity.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="google_sheet_outer">
        <div className="container">
          <div className="row">
            <div className="col-md-10 offset-md-1" dangerouslySetInnerHTML={{ __html: aboutUsContent }}>
            </div>
          </div>
          <div className="row mt-5">
            <div className="col-md-10 offset-md-1">
              <div className="main_title_text">
                Trusted by Advisors, Agencies, and Growing Businesses
              </div>
              <p className="desc_text">From solo practitioners to multi-office firms, OPTCS scales with your needs. Our platform is designed to grow alongside your business, adding connectors and reports as your data sources expand.</p>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 offset-md-3">
              <img className="img-fluid mt-5" src="./rings.png" alt="Connected platforms" />
            </div>
          </div>
        </div>
      </section>
      <section className="banner_section mt-0 pt-5 pb-5">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-8 offset-md-2 col-sm-12">
              <div className="banner_left text-center pr-0">
                <h1 className="mb-3 testimonial_title">
                  <span>What Our Customers Say</span></h1>
                <p className="mb-4">Business owners and advisors rely on OPTCS to cut through the noise and focus on the numbers that drive growth.</p>
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-10 offset-md-1 col-sm-12">
              <Swiper
                spaceBetween={30}
                slidesPerView={2}
                breakpoints={{
                  0: { slidesPerView: 1 },
                  400: { slidesPerView: 1 },
                  639: { slidesPerView: 1 },
                  865: { slidesPerView: 2 }
                }}
              >
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>OPTCS replaced three different spreadsheets and a weekly reporting meeting. Now I open one dashboard and know exactly where my business stands.</p>
                    <div className="testimonial_name">Sarah Mitchell</div>
                    <p className="testimonial_desg mb-0">Financial Advisor</p>
                  </div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>The QuickBooks integration alone saved us hours every week. Having sales and financial reports in the same place changed how we run our practice.</p>
                    <div className="testimonial_name">David Chen</div>
                    <p className="testimonial_desg mb-0">Managing Partner</p>
                  </div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>I finally have visibility into marketing ROI alongside production numbers. OPTCS makes it simple to see what is working and what is not.</p>
                    <div className="testimonial_name">Jennifer Okafor</div>
                    <p className="testimonial_desg mb-0">Agency Owner</p>
                  </div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>Setting up connectors took ten minutes. Within an hour I had a full overview of our sales pipeline, operations metrics, and cash position.</p>
                    <div className="testimonial_name">Marcus Reed</div>
                    <p className="testimonial_desg mb-0">Business Consultant</p>
                  </div>
                </SwiperSlide>
              </Swiper>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default AboutUsPage;
