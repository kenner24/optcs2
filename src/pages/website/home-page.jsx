import { Swiper, SwiperSlide } from "swiper/react";
import { Link } from "react-router-dom";

function Homepage() {
  return (
    <>
      <section className="banner_section banner_main_outer">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-6">
              <div className="banner_left">
                <h1 className="mb-3">Your Business Data,<br />
                  <span>One Clear View</span></h1>
                <p className="mb-4">OPTCS connects your accounting software, spreadsheets, and CRM into a single dashboard so you can track sales, marketing, financials, and operations without the manual work.</p>
                <div className="btns_div">
                  <Link className="login" to="/register">Start Free Trial</Link>
                  <Link className="signup" to="/login">Sign In</Link>
                </div>
              </div>
            </div>
            <div className="col-md-6">
              <div className="banner_right">
                <img className="img-fluid" src="./banner_right.png" alt="OPTCS Dashboard Preview" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="google_sheet_outer">
        <div className="container">
          <div className="row">
            <div className="col-md-10 offset-md-1">
              <div className="main_title_text">
                Connect Your Data Sources in Minutes
              </div>
              <p className="desc_text">OPTCS integrates directly with QuickBooks, Google Sheets, and other platforms you already use. No complicated setup, no data migration headaches. Just connect your accounts and start seeing your numbers in one place.</p>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 offset-md-3">
              <img className="img-fluid mt-5" src="./rings.png" alt="Data integrations" />
            </div>
          </div>
        </div>
      </section>

      <section className="banner_section mt-0">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-6">
              <div className="banner_left">
                <h1 className="mb-3">
                  <span>Prime Hub</span></h1>
                <p className="mb-0">Your central command center for every metric that matters. See open opportunities, lead flow, production numbers, and client data all in one place. Stop toggling between spreadsheets and software. Prime Hub brings it all together.</p>
              </div>
            </div>
            <div className="col-md-6">
              <div className="banner_right">
                <img className="img-fluid" src="./prime_hub.png" alt="Prime Hub Dashboard" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="google_sheet_outer">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-6">
              <div className="banner_right">
                <img className="img-fluid" src="./build_share.png" alt="Professional Reports" />
              </div>
            </div>
            <div className="col-md-6">
              <div className="banner_left pl-4 pr-0">
                <div className="main_title_text text-left">
                  Build & Share Professional
                  Reports
                </div>
                <p className="desc_text subdesc_text">Generate polished reports across sales, marketing, financial, and operations categories. Share them with your team, partners, or clients. Every chart and table pulls from live data so your reports are always current.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="google_sheet_outer pt-0">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-6">
              <div className="banner_left pl-0 pr-4">
                <div className="main_title_text text-left">
                  Real-Time Data
                </div>
                <p className="desc_text subdesc_text">
                  Your dashboard updates as your data changes. Track leads per week, submitted production, cash flow forecasts, pending business, and dozens of other KPIs with charts that reflect what is happening right now, not last month.
                </p>
              </div>
            </div>
            <div className="col-md-6">
              <div className="banner_right pl-0 pr-0">
                <img className="img-fluid" src="./realtime_data.png" alt="Real-time data visualization" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="google_sheet_outer">
        <div className="container">
          <div className="row">
            <div className="col-md-10 offset-md-1">
              <div className="main_title_text">
                Reports Built for Every Department
              </div>
            </div>
          </div>
          <div className="row mt-4">
            <div className="col-md-3 col-sm-6 mb-4">
              <div className="testimonial_div text-center" style={{padding: "30px 20px"}}>
                <img src="./d1.png" alt="Sales" style={{width: "50px", marginBottom: "15px"}} />
                <h6 style={{fontWeight: 500, color: "#000"}}>Sales</h6>
                <p style={{fontSize: "13px", color: "#6E7D8C"}}>Open opportunities, submitted production, pending business, paid annuity, and YTD comparisons.</p>
              </div>
            </div>
            <div className="col-md-3 col-sm-6 mb-4">
              <div className="testimonial_div text-center" style={{padding: "30px 20px"}}>
                <img src="./d2.png" alt="Marketing" style={{width: "50px", marginBottom: "15px"}} />
                <h6 style={{fontWeight: 500, color: "#000"}}>Marketing</h6>
                <p style={{fontSize: "13px", color: "#6E7D8C"}}>Lead generation, seminar response rates, cost per client, show rates, and funnel performance.</p>
              </div>
            </div>
            <div className="col-md-3 col-sm-6 mb-4">
              <div className="testimonial_div text-center" style={{padding: "30px 20px"}}>
                <img src="./d3.png" alt="Financial" style={{width: "50px", marginBottom: "15px"}} />
                <h6 style={{fontWeight: 500, color: "#000"}}>Financial</h6>
                <p style={{fontSize: "13px", color: "#6E7D8C"}}>Cash on hand, monthly expenses, profitability percentages, cash flow forecasts, and liabilities.</p>
              </div>
            </div>
            <div className="col-md-3 col-sm-6 mb-4">
              <div className="testimonial_div text-center" style={{padding: "30px 20px"}}>
                <img src="./d4.png" alt="Operations" style={{width: "50px", marginBottom: "15px"}} />
                <h6 style={{fontWeight: 500, color: "#000"}}>Operations</h6>
                <p style={{fontSize: "13px", color: "#6E7D8C"}}>Days to issue, review preparation tracking, and new assets from existing clients.</p>
              </div>
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
                  0: {
                    slidesPerView: 1,
                  },
                  400: {
                    slidesPerView: 1,
                  },
                  639: {
                    slidesPerView: 1,
                  },
                  865: {
                    slidesPerView: 2,
                  }
                }}
              >
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>OPTCS replaced three different spreadsheets and a weekly reporting meeting. Now I open one dashboard and I know exactly where my business stands.</p>
                    <div className="testimonial_name">
                      Sarah Mitchell
                    </div>
                    <p className="testimonial_desg mb-0">Financial Advisor</p>
                  </div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>The QuickBooks integration alone saved us hours every week. Having sales and financial reports in the same place changed how we run our practice.</p>
                    <div className="testimonial_name">
                      David Chen
                    </div>
                    <p className="testimonial_desg mb-0">Managing Partner</p>
                  </div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>I finally have visibility into marketing ROI alongside production numbers. OPTCS makes it simple to see what is working and what is not.</p>
                    <div className="testimonial_name">
                      Jennifer Okafor
                    </div>
                    <p className="testimonial_desg mb-0">Agency Owner</p>
                  </div>
                </SwiperSlide>
                <SwiperSlide>
                  <div className="testimonial_div mt-2">
                    <img className="comma_img" src="./commas.png" alt="quote" />
                    <p>Setting up connectors took ten minutes. Within an hour I had a full overview of our sales pipeline, operations metrics, and cash position.</p>
                    <div className="testimonial_name">
                      Marcus Reed
                    </div>
                    <p className="testimonial_desg mb-0">Business Consultant</p>
                  </div>
                </SwiperSlide>
              </Swiper>
            </div>
          </div>
        </div>
      </section>

      <section className="google_sheet_outer">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-8 offset-md-2 text-center">
              <div className="main_title_text">
                Ready to See Your Business Clearly?
              </div>
              <p className="desc_text mb-4">Start your free trial today. No credit card required. Connect your data and have your first dashboard running in under 15 minutes.</p>
              <div className="btns_div" style={{justifyContent: "center", display: "flex", gap: "15px"}}>
                <Link className="login" to="/register" style={{display: "inline-block", textAlign: "center"}}>Get Started Free</Link>
                <Link className="signup" to="/contact-us" style={{display: "inline-block", textAlign: "center"}}>Contact Sales</Link>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default Homepage;
