import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import { useDispatch } from "react-redux";
import { useEffect, useState } from "react";
import { toast } from "react-toastify";
import { UserTypeConstant } from "../../constants/userType.constant";
import { useGetUserProfileQuery, useUpdateProfileMutation } from "../../redux/api/user/user-api";
import { setUserDetails } from "../../redux/slices/UserProfileSlice";

function AccountDetail() {
  const dispatch = useDispatch();
  const { data: userProfile, refetch } = useGetUserProfileQuery();

  const [UpdateProfile] = useUpdateProfileMutation();
  const [name, setName] = useState(" ");
  const [username, setUsername] = useState(" ");
  const [email, setEmail] = useState(" ");
  const [companyName, setCompanyName] = useState(" ");
  const [workEmail, setWorkEmail] = useState(" ");
  const [totalEmp, setTotalEmp] = useState(" ");
  const [domainSec, setDomainSec] = useState(" ");
  const [userType, setUserType] = useState(" ");
  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
      await UpdateProfile({
        name,
        username,
        company_name: companyName,
        work_email: workEmail,
        total_employees: totalEmp,
        domain_sector: domainSec
      })
        .unwrap()
        .then((res) => {
          toast(res?.message || res?.data?.message);
          refetch();
        })
        .catch((err) => {
          if (err?.status === 422 && err?.data?.errors) {
            for (const property in err?.data?.errors) {
              toast(err?.data?.errors[property][0]);
              return;
            }
          }
          toast(err?.data?.message);
        });
    } catch (error) {
      toast(error?.message || "Something went wrong");
    }
  }

  useEffect(() => {
    if (userProfile?.data) {
      dispatch(setUserDetails(userProfile?.data));
      setName(userProfile?.data?.name);
      setUsername(userProfile?.data?.username);
      setEmail(userProfile?.data?.email);
      setCompanyName(userProfile?.data?.company_name || userProfile?.data?.company_details?.company_name);
      setWorkEmail(userProfile?.data?.work_email);
      setTotalEmp(userProfile?.data?.total_employees);
      setDomainSec(userProfile?.data?.domain_sector);
      setUserType(userProfile?.data?.type);
    }
  }, [userProfile]);

  return (
    <div className="financial_div_custom">
      <Form onSubmit={handleSubmit}>
        <Form.Group className="mb-3" controlId="formName">
          <Form.Label>Name</Form.Label>
          <Form.Control
            type="text"
            placeholder="Name"
            onChange={(e) => setName(e.target.value)}
            value={name}
          />
        </Form.Group>

        <Form.Group className="mb-3" controlId="formUsername">
          <Form.Label>Username</Form.Label>
          <Form.Control
            type="text"
            placeholder="Username"
            onChange={(e) => setUsername(e.target.value)}
            value={username}
          />
        </Form.Group>

        <Form.Group className="mb-3" controlId="formEmail">
          <Form.Label>Email</Form.Label>
          <Form.Control
            type="email"
            placeholder="Email"
            readOnly
            disabled
            onChange={(e) => setEmail(e.target.value)}
            value={email}
          />
        </Form.Group>

        {
          userType === UserTypeConstant.STAFF
            ?
            <Form.Group className="mb-3" controlId="formCompanyName">
              <Form.Label>Company Name</Form.Label>
              <Form.Control
                type="text"
                placeholder="Company Name"
                onChange={(e) => setCompanyName(e.target.value)}
                value={companyName}
                readOnly
                disabled
              />
            </Form.Group>
            :
            <Form.Group className="mb-3" controlId="formCompanyName">
              <Form.Label>Company Name</Form.Label>
              <Form.Control
                type="text"
                placeholder="Company Name"
                onChange={(e) => setCompanyName(e.target.value)}
                value={companyName}
              />
            </Form.Group>
        }

        <Form.Group className="mb-3" controlId="formWorkEmail">
          <Form.Label>Work Email</Form.Label>
          <Form.Control
            type="email"
            placeholder="Work Email"
            onChange={(e) => setWorkEmail(e.target.value)}
            value={workEmail}
          />
        </Form.Group>

        {
          userType === UserTypeConstant.COMPANY
          &&
          <>
            <Form.Group className="mb-3" controlId="formTotalEmp">
              <Form.Label>Total Emp.</Form.Label>
              <Form.Control
                type="number"
                placeholder="Total Employees"
                onChange={(e) => setTotalEmp(e.target.value)}
                value={totalEmp}
                min="0"
              />
            </Form.Group>

            <Form.Group className="mb-3" controlId="formDomainSec">
              <Form.Label>Domain Sector</Form.Label>
              <Form.Control
                type="text"
                placeholder="Domain Sector"
                onChange={(e) => setDomainSec(e.target.value)}
                value={domainSec}
              />
            </Form.Group>
          </>
        }

        <Button variant="primary" type="submit">
          Submit
        </Button>
      </Form>
    </div>
  )
}

export default AccountDetail;