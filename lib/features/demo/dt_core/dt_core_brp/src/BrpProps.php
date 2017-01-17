<?php

namespace Drupal\dt_core_brp;

/**
 * Class BrpProps.
 *
 * Helper model class to store all of constant data for Brp project.
 */
class BrpProps {
  // CLIENTS CONNECTION.
  const CONNECTION_TYPE = 'brp_ws';
  const CONNECTION_NAME = 'brp';
  const CONNECTION_SETTINGS_CACHE = 21600;

  // SERVICES.
  const SERVICE_LIST = '_links';
  const SERVICE_SELF = 'self';
  const SERVICE_INITIATIVES = 'initiativesV1';
  const SERVICE_FEEDBACK = 'feedbackV1';
  const SERVICE_ATTACHMENT = 'attachment';
  const SERVICE_FEEDBACK_REPORT = 'reportsV1';

  // SERVICES SETTINGS.
  const SERVICE_INITIATIVES_DEFAULT_SIZE = 20;

  // METADATA ENDPOINTS.
  const META_LIST = 'profile';
  const META_INITIATIVES = 'profile/initiativesV1';
  const META_FEEDBACK = 'profile/feedbackV1';
  const META_FEEDBACK_REPORT = 'profile/reportsV1';

  // FEEDBACK DESCRIPTORS.
  const FEEDBACKS_BY_INITIATIVEID = 'feedbackV1/search/findByInitiativeId?id=';
  const FEEDBACKS_VERSION = 'feedbackV1';
  const FEEDBACK_EMBEDDED = '_embedded';

  // FEEDBACK PAGE.
  const FEEDBACK_PAGE = 'page';
  const FEEDBACK_PAGE_TOTALELEMENTS = 'totalElements';
  const FEEDBACK_PAGE_SIZE = 'size';
  const FEEDBACK_PAGE_TOTALPAGES = 'totalPages';
  const FEEDBACK_PAGE_NUMBER = 'totalPages';

  const INITIATIVES_ATTACHMENTS = 'attachments';
  const INITIATIVES_ATTACHMENTS_TYPE = 'type';
  const INITIATIVES_ATTACHMENTS_TYPE_MAIN = 'MAIN';
  const INITIATIVES_ATTACHMENTS_TYPE_ANNEX = 'ANNEX';
  const INITIATIVES_ATTACHMENTS_LANGUAGE = 'language';
  const INITIATIVES_ATTACHMENTS_DOCTYPE = 'PDF';
  const INITIATIVES_ATTACHMENTS_PAGES = 'pages';
  const INITIATIVES_ATTACHMENTS_SIZE = 'size';
  const INITIATIVES_ATTACHMENTS_DOCID = 'documentId';

  // ENTITYFORMS SUBMISSION CONSTANTS.
  const FEEDBACK_INITIATIVE_PATH = '/api/initiativesV1/';
  const REPORT_FEEDBACK_PATH = '/api/feedbackV1/';

  // METADATA DESCRIPTORS IDs.
  const META_DESC_INITIATIVE = 'initiativeV1-representation';
  const META_DESC_FEEDBACK = 'feedbackV1-representation';
  const META_DESC_FEEDBACK_REPORT = 'reportV1-representation';

  // REQUESTS.
  const REQUEST_GET = "GET";
  const REQUEST_POST = "POST";

  // RESPONSES.
  const RESPONSE_CODE_OK = 200;
  const RESPONSE_ERROR_DESC = 'REST request error';

  // CLIENT NODE INTEGRATION.
  const NODE_INTEGRATION_DISABLED = 0;

  // CONTENT TYPES.
  const INITIATIVE_CT_ENTITY_TYPE = 'node';
  const INITIATIVE_CT_BUNDLE = 'brp_initiative';
  const FEEDBACK_FORM_ENTITY_TYPE = 'entityform';
  const FEEDBACK_FORM_BUNDLE = 'brp_initiatives_feedback';
  const FEEDBACK_REPORT_FORM_ENTITY_TYPE = 'entityform';
  const FEEDBACK_REPORT_FORM_BUNDLE = 'brp_initiatives_feedback_report';

  // CONTENT FIELDS.
  const INITIATIVE_FIELD_BRP_ID = 'field_brp_inve_id';

  // TR PATH.
  const FEEDBACK_TR_PATH = 'http://ec.europa.eu/transparencyregister/public/consultation/displaylobbyist.do';

  // INITIATIVE SPECIFIC CONSTANTS.
  const BRP_INITIATIVE_OPEN = 'open';
  const BRP_INITIATIVE_UPCOMING = 'upcoming';
  const BRP_INITIATIVE_CLOSED = 'closed';
  const BRP_INITIATIVE_FEEDBACK_PATH = 'feedback';
  const BRP_INITIATIVE_REPORT_FEEDBACK_PATH = 'report';

  // FEEDBACKFIELD CONSTANTS.
  const BRP_FEEDBACKFIELD_TRIM = 300;
  const BRP_FEEDBACKFIELD_ROW = 6;
  const BRP_FEEDBACKFORM_ERROR_DEFAULT = 'Complete these fields and try again.';
  const BRP_FEEDBACKFORM_ERROR_DEFAULT_TITLE = 'Some required information is missing or incomplete';

}
