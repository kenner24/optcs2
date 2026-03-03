export default function FaqCard({ question, answer, queNum }) {
  return (
    <>
      <div className="card">
        <div className="card-header" id={`faqhead${queNum}`}>
          <a
            className="btn btn-header-link collapsed"
            data-toggle="collapse"
            data-target={`#faq${queNum}`}
            aria-expanded="false"
            aria-controls={`faq${queNum}`}
          >
            <span dangerouslySetInnerHTML={{ __html: question }}></span>
            <i className="far fa-plus"></i>
            <i className="far fa-minus"></i>
          </a>
        </div>

        <div id={`faq${queNum}`} className="collapse" aria-labelledby={`faqhead${queNum}`} data-parent="#faq">
          <div className="card-body" dangerouslySetInnerHTML={{ __html: answer }}>
          </div>
        </div>
      </div>
    </>
  );
}