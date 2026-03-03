import { useState } from "react";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import { useChangePasswordMutation } from "../../redux/api/user/user-api";
import { toast } from "react-toastify";


function ChangePassword() {
  const [ChangePassword] = useChangePasswordMutation();
  const [oldPassword, setOldPassword] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");

  const handleSubmit = async (event) => {
    event.preventDefault();
    try {

      if (password !== confirmPassword) {
        toast("Password and Confirm password did not match");
      }

      await ChangePassword({
        "old_password": oldPassword,
        "password": password,
        "password_confirmation": confirmPassword
      }).unwrap()
        .then((res) => {
          toast(res?.message || res?.data?.message);
          setOldPassword("");
          setPassword("");
          setConfirmPassword("");
        })
        .catch((err) => {
          toast(err?.data?.message);
        });
    } catch (error) {
      toast(error?.message || "Something went wrong");
    }
  }

  return (
    <div className="financial_div_custom">
      <Form onSubmit={handleSubmit}>
        <Form.Group className="mb-3" controlId="formOldPassword">
          <Form.Label>Old Password</Form.Label>
          <Form.Control
            type="password"
            placeholder="Old Password"
            onChange={(e) => setOldPassword(e.target.value)}
            value={oldPassword}
            required
          />
        </Form.Group>
        <Form.Group className="mb-3" controlId="formPassword">
          <Form.Label>Password</Form.Label>
          <Form.Control
            type="password"
            placeholder="Password"
            onChange={(e) => setPassword(e.target.value)}
            value={password}
            required
          />
        </Form.Group>
        <Form.Group className="mb-3" controlId="formConfirmPassword">
          <Form.Label>Confirm Password</Form.Label>
          <Form.Control
            type="password"
            placeholder="Confirm Password"
            onChange={(e) => setConfirmPassword(e.target.value)}
            value={confirmPassword}
            required
          />
        </Form.Group>
        <Button variant="primary" type="submit">
          Submit
        </Button>
      </Form>
    </div>
  )
}

export default ChangePassword