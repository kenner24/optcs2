import { useState } from "react";

function ContactUsPage() {
  const [formData, setFormData] = useState({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    message: "",
  });
  const [submitted, setSubmitted] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setSubmitted(true);
  };

  return (
    <>
      <section className="banner_section banner_main_outer terms_banner">
        <div className="container">
          <div className="row align-items-center">
            <div className="col-md-8 offset-md-2 col-sm-12">
              <div className="banner_left text-center">
                <h1 className="mb-3">Contact
                  <span> Us</span></h1>
                <p className="mb-4">Have a question about OPTCS? Want to schedule a demo or talk to our team? We would love to hear from you.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section className="google_sheet_outer">
        <div className="container">
          <div className="row">
            <div className="col-md-10 offset-md-1 col-sm-12">
              <div className="testimonial_div mb-0 contact_div_main">
                <div className="contact_address_div1">
                  <div className="contact_upper_div">
                    <div className="contact_address_divinner">
                      <div className="contact_col">
                        <img src="./c1.png" alt="Address" />
                        <span><p>OPTCS Headquarters<br /></p></span>
                      </div>
                    </div>
                    <div className="contact_address_divinner contact_address_center">
                      <div className="contact_col">
                        <img src="./c2.png" alt="Phone" />
                        <span><p>Contact us online<br /></p></span>
                      </div>
                    </div>
                    <div className="contact_address_divinner">
                      <div className="contact_col">
                        <img src="./c3.png" alt="Email" />
                        <span><p>support@optcs.com<br /></p></span>
                      </div>
                    </div>
                  </div>
                  <div className="contact_lower_div">
                    <div className="testimonial_circle flash"></div>
                    {submitted ? (
                      <div className="text-center" style={{padding: "40px 0"}}>
                        <img src="./green_tick.png" alt="Success" style={{width: "60px", marginBottom: "20px"}} />
                        <h4 style={{color: "#000", fontWeight: 500}}>Thank You!</h4>
                        <p style={{color: "#6E7D8C"}}>Your message has been received. Our team will get back to you within one business day.</p>
                        <button className="login_btn view_more_btn" onClick={() => setSubmitted(false)}>Send Another Message</button>
                      </div>
                    ) : (
                      <form onSubmit={handleSubmit}>
                        <div className="row">
                          <div className="col-md-6">
                            <label className="custom_label">First Name</label>
                            <input className="custom_input" type="text" name="firstName" placeholder="Enter First Name" value={formData.firstName} onChange={handleChange} required />
                          </div>
                          <div className="col-md-6">
                            <label className="custom_label">Last Name</label>
                            <input className="custom_input" type="text" name="lastName" placeholder="Enter Last Name" value={formData.lastName} onChange={handleChange} required />
                          </div>
                        </div>
                        <div className="row">
                          <div className="col-md-6">
                            <label className="custom_label">Email</label>
                            <input className="custom_input" type="email" name="email" placeholder="Enter Email Address" value={formData.email} onChange={handleChange} required />
                          </div>
                          <div className="col-md-6">
                            <label className="custom_label">Phone No.</label>
                            <input className="custom_input" type="tel" name="phone" placeholder="Enter Phone Number" value={formData.phone} onChange={handleChange} />
                          </div>
                        </div>
                        <div className="row">
                          <div className="col-md-12">
                            <label className="custom_label">Message</label>
                            <textarea className="custom_input" name="message" placeholder="Tell us how we can help..." rows="4" style={{minHeight: "100px", resize: "vertical"}} value={formData.message} onChange={handleChange} required></textarea>
                          </div>
                        </div>
                        <div className="submit_btn mt-3">
                          <button type="submit" className="login_btn view_more_btn">Send Message</button>
                        </div>
                      </form>
                    )}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default ContactUsPage;
