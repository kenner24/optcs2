import React, { useState } from "react";
import Button from "react-bootstrap/Button";
import Modal from "react-bootstrap/Modal";
import Form from "react-bootstrap/Form";
import { toast } from "react-toastify";
import { useCreateStaffMutation } from "../../redux/api/staff/staff-api";
import { Spinner } from "react-bootstrap";

function AddStaffModal(props) {
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const [name, setName] = useState();
  const [email, setEmail] = useState();
  const [nameError, setNameError] = useState();
  const [emailError, setEmailError] = useState();

  const [createStaff, { isLoading }] = useCreateStaffMutation();
  const notify = (text) => toast(text);
  const handlePostRequest = async () => {
    try {
      const data = { name, email };
      await createStaff(data)
        .unwrap()
        .then((res) => {
          handleClose();
          setName("");
          setEmail("");
          props.refetch();
          notify(res?.message);
        })
        .catch((err) => {
          if (err.status === 404) {
            notify(err?.data?.message);
          }
          if (err.status === 400) {
            for (var val of Object.keys(err.data.data)) {
              notify(err.data.data[val][0]);
            }
          }
        });
    } catch (error) {
      notify(error.message);
    }
  };

  const prepareInput = () => {
    if (typeof name === "undefined") {
      setNameError("Name is required");
    } else {
      setNameError("");
    }

    if (typeof email === "undefined") {
      setEmailError("Email is required");
    } else {
      setEmailError("");
    }

    if (!nameError && !emailError) {
      handlePostRequest();
    }
  };

  return (
    <>
      <Button variant="primary" onClick={handleShow}>
        Add +
      </Button>
      <Modal show={show} onHide={handleClose}>
        <Modal.Header className="modal_header_custom" closeButton>
          <Modal.Title>Add</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form.Group>
            <Form.Label className="custom_label1">Name</Form.Label>
            <Form.Control
              className="custom_input mb-3"
              placeholder="Enter Name"
              aria-label="name"
              aria-describedby="basic-addon1"
              onChange={(e) => setName(e.target.value)}
            />
            <span className="text-danger">{nameError}</span>
          </Form.Group>

          <Form.Group>
            <Form.Label className="custom_label1">Email</Form.Label>
            <Form.Control
              className="custom_input mb-3"
              placeholder="Enter Email"
              aria-label="email"
              aria-describedby="basic-addon1"
              onChange={(e) => setEmail(e.target.value)}
            />
            <span className="text-danger">{emailError}</span>
          </Form.Group>
          <Button variant="primary" className="create_btn" onClick={prepareInput}>
            Create {" "}
            {isLoading ? <Spinner animation="border" size="sm" /> : ""}
          </Button>
        </Modal.Body>
      </Modal>
    </>
  );
}

export default AddStaffModal;
