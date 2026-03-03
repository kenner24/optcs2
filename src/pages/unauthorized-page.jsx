import { Link } from "react-router-dom";

export default function UnauthorizedPage() {
  return (
    <div id="error-page">
      <h1>Unauthorized</h1>
      <Link to={"/overview"}>Go To Dashboard</Link>
    </div>
  );
}