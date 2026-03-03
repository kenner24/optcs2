import { Link } from "react-router-dom";

export default function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="custom_footer">
      <div className="container">
        <div className="row">
          <div className="col-md-12 footer_col">
            <div className="footer_div text-left">
              <img className="mb-2" src="./footer_logo.png" alt="OPTCS" />
              <p>OPTCS connects your business data into one clear dashboard. Track sales, marketing, financials, and operations in real time with integrations for QuickBooks, Google Sheets, and more.</p>
              <p className="copyright_text">&copy; {currentYear} OPTCS. All rights reserved.</p>
            </div>
            <div className="footer_div">
              <div className="footer_title">Menu</div>
              <ul className="footer_ul">
                <li><Link to={"/"}>Home</Link></li>
                <li><Link to={"/about-us"}>About Us</Link></li>
                <li><Link to={"/contact-us"}>Contact Us</Link></li>
              </ul>
            </div>
            <div className="footer_div">
              <div className="footer_title">Info</div>
              <ul className="footer_ul">
                <li><Link to={"/terms-and-condition"}>Terms of Use</Link></li>
                <li><Link to={"/privacy-policy"}>Privacy Policy</Link></li>
                <li><Link to={"/faq"}>FAQ</Link></li>
              </ul>
            </div>
            <div className="footer_div">
              <div className="footer_title">Follow Us</div>
              <ul className="social_ul">
                <li><a href="https://facebook.com" target="_blank" rel="noopener noreferrer"><i className="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="https://twitter.com" target="_blank" rel="noopener noreferrer"><i className="fa-brands fa-twitter"></i></a></li>
                <li><a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"><i className="fa-brands fa-linkedin-in"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}
